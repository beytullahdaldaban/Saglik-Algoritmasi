// --- DOM Elementlerini Seçelim ---
const aramaKutusu = document.getElementById("aramaKutusu");
const sonucAlani = document.getElementById("sonucAlani");

if (aramaKutusu) {
    aramaKutusu.addEventListener("keypress", function(event) {
        if (event.key === "Enter") gidaAra();
    });
}

// --- GIDA ARAMA MOTORU ---
async function gidaAra() {
    let aranan = aramaKutusu.value.trim();
    if (aranan.length < 2) { Swal.fire('Uyarı', 'En az 2 harf giriniz.', 'warning'); return; }

    sonucAlani.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-primary"></div><p>Aranıyor...</p></div>`;

    try {
        const response = await fetch(`https://tr.openfoodfacts.org/cgi/search.pl?search_terms=${aranan}&search_simple=1&action=process&json=1&page_size=24`);
        const data = await response.json();
        sonucAlani.innerHTML = '';

        if (data.products.length === 0) {
            sonucAlani.innerHTML = `<div class="alert alert-warning text-center">"${aranan}" bulunamadı.</div>`;
            return;
        }

        let html = '<div class="row g-4">';
        
        // Yardımcı Fonksiyon: Gelen veri sayı mı yazı mı bakmaz, sayıya çevirir.
        const sayiYap = (deger) => {
            let s = parseFloat(deger);
            return isNaN(s) ? 0 : s;
        };

        data.products.forEach(urun => {
            // Verileri Al
            let ad = urun.product_name || "İsimsiz Ürün";
            let marka = urun.brands || "Belirsiz";
            let resim = urun.image_front_url || urun.image_url || "https://placehold.co/300x200?text=Resim+Yok";

            // Makroları Al (HATA BURADAYDI - DÜZELTİLDİ)
            let nut = urun.nutriments || {};
            
            // Artık önce sayiYap() ile garantiye alıyoruz
            let kalori = Math.round(sayiYap(nut['energy-kcal_100g']));
            let seker = sayiYap(nut.sugars_100g).toFixed(1);
            let protein = sayiYap(nut.proteins_100g).toFixed(1);
            let yag = sayiYap(nut.fat_100g).toFixed(1);
            let karb = sayiYap(nut.carbohydrates_100g).toFixed(1);
            let tuz = sayiYap(nut.salt_100g).toFixed(1);

            // Verileri ŞİFRELE
            let safeData = encodeURIComponent(JSON.stringify({
                ad, marka, resim, kalori, seker, protein, yag, karb, tuz
            }));

            html += `
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="bg-light p-3 text-center" style="height:180px;">
                        <img src="${resim}" onerror="this.src='https://placehold.co/300?text=Hata'" class="img-fluid" style="max-height:100%;">
                    </div>
                    <div class="card-body">
                        <small class="text-muted fw-bold">${marka}</small>
                        <h6 class="fw-bold mb-3 text-truncate" title="${ad}">${ad}</h6>
                        <div class="row text-center mb-3 g-1" style="font-size:0.8rem">
                            <div class="col-4 border-end"><b>${kalori}</b><br>Kcal</div>
                            <div class="col-4 border-end"><b class="text-primary">${protein}</b><br>Prot</div>
                            <div class="col-4"><b class="text-danger">${yag}</b><br>Yağ</div>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-sm btn-info text-white fw-bold" onclick="detayAc('${safeData}')">
                                <i class="fa-solid fa-eye"></i> İncele
                            </button>
                            <button class="btn btn-sm btn-outline-primary fw-bold" onclick="kaydet('${safeData}', 'analiz')">
                                <i class="fa-solid fa-chart-pie"></i> Analizlere Ekle
                            </button>
                            <button class="btn btn-sm btn-success fw-bold" onclick="kaydet('${safeData}', 'tuketim')">
                                <i class="fa-solid fa-calendar-check"></i> Kalori Takibe Ekle
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
        });
        sonucAlani.innerHTML = html + '</div>';

    } catch (e) { 
        console.error(e); 
        sonucAlani.innerHTML = `<div class="alert alert-danger">Hata: ${e.message}</div>`; 
    }
}

// --- KAYDETME FONKSİYONU ---
async function kaydet(safeData, tur) {
    let veri = JSON.parse(decodeURIComponent(safeData));
    
    // Giriş Kontrolü
    const girisYapti = (typeof UYE_GIRIS_YAPTI !== 'undefined') ? UYE_GIRIS_YAPTI : false;
    
    if (tur === 'tuketim' && !girisYapti) {
        Swal.fire({ icon:'warning', title:'Giriş Yapmalısın', showCancelButton:true, confirmButtonText:'Giriş Yap' })
        .then((r) => { if(r.isConfirmed) location.href='uyelik/giris.php'; });
        return;
    }

    const { value: gramaj } = await Swal.fire({
        title: 'Kaç Gram?', text: veri.ad, input: 'number', inputValue: 100, confirmButtonText: 'Ekle'
    });
    if (!gramaj) return;

    let oran = gramaj / 100;
    let paket = {
        urun_adi: `${veri.ad} (${gramaj}g)`,
        marka: veri.marka,
        resim: veri.resim,
        kalori: Math.round(veri.kalori * oran),
        seker: (veri.seker * oran).toFixed(1),
        protein: (veri.protein * oran).toFixed(1),
        yag: (veri.yag * oran).toFixed(1),
        karb: (veri.karb * oran).toFixed(1),
        tuz: (veri.tuz * oran).toFixed(1),
        tur: tur
    };

    // MİSAFİR MODU
    if (!girisYapti && tur === 'analiz') {
        let liste = JSON.parse(localStorage.getItem('misafir_analizleri')) || [];
        liste.push({ ...paket, id: Date.now() });
        localStorage.setItem('misafir_analizleri', JSON.stringify(liste));
        Swal.fire({ icon:'success', title:'Eklendi', timer:1500, showConfirmButton:false }).then(() => location.reload()); 
        return;
    }

    // ÜYE MODU
    try {
        const req = await fetch('islemler/kaydet.php', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(paket)
        });
        const res = await req.json();

        if (res.durum === 'basarili') {
            Swal.fire({ icon:'success', title:'Kaydedildi', timer:1500, showConfirmButton:false }).then(() => location.reload());
        } else {
            Swal.fire('Hata', res.mesaj, 'error');
        }
    } catch (e) { console.error(e); Swal.fire('Hata', 'Sunucu hatası.', 'error'); }
}

// --- DETAY GÖSTER ---
function detayAc(safeData) {
    let veri = JSON.parse(decodeURIComponent(safeData));
    Swal.fire({
        title: `<h5 class="text-dark">${veri.ad}</h5>`,
        html: `
            <img src="${veri.resim}" style="height:100px" class="mb-3 rounded">
            <table class="table table-sm table-bordered">
                <tr><td>Kalori</td><th>${veri.kalori} kcal</th></tr>
                <tr><td>Protein</td><th>${veri.protein} g</th></tr>
                <tr><td>Yağ</td><th>${veri.yag} g</th></tr>
                <tr><td>Karb</td><th>${veri.karb} g</th></tr>
                <tr><td>Şeker</td><th>${veri.seker} g</th></tr>
            </table>
        `
    });
}