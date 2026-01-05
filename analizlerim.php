<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 

// Verileri Hazırla
$gidalar = [];
if(isset($_SESSION['uye_id'])) {
    $uye_id = $_SESSION['uye_id'];
    $sorgu = $db->prepare("SELECT * FROM gidalar WHERE uye_id = ? AND tur = 'analiz' ORDER BY id DESC");
    $sorgu->execute([$uye_id]);
    $gidalar = $sorgu->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container py-5">
    
    <?php if(!isset($_SESSION['uye_id'])): ?>
    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4 rounded-4">
        <i class="fa-solid fa-cookie-bite fa-2x me-3 text-info"></i>
        <div>
            <strong>Misafir Modundasın:</strong> Veriler sadece bu cihazda saklanır. <a href="uyelik/kayit.php" class="fw-bold">Üye Ol</a>.
        </div>
    </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">📊 Besin Komuta Merkezi</h2>
            <p class="text-muted">Analiz listen ve makro dağılımın.</p>
        </div>
        <button onclick="listeyiTemizle()" class="btn btn-outline-danger btn-sm rounded-pill px-3">
            <i class="fa-solid fa-trash me-2"></i> Temizle
        </button>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-3"><div class="card border-0 shadow-sm bg-dark text-white h-100 rounded-4 p-4"><h6 class="opacity-75">TOPLAM KALORİ</h6><h1 class="fw-bold display-4 mb-0" id="toplamKalori">0</h1><small>kcal</small></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm bg-primary text-white h-100 rounded-4 p-4"><h5>Protein</h5><h2 class="fw-bold mb-0"><span id="toplamProtein">0</span>g</h2><div class="progress mt-3 bg-white bg-opacity-25" style="height:6px"><div id="barProtein" class="progress-bar bg-white" style="width:0%"></div></div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm bg-warning text-dark h-100 rounded-4 p-4"><h5>Karbonhidrat</h5><h2 class="fw-bold mb-0"><span id="toplamKarb">0</span>g</h2><div class="progress mt-3 bg-dark bg-opacity-10" style="height:6px"><div id="barKarb" class="progress-bar bg-dark" style="width:0%"></div></div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm bg-danger text-white h-100 rounded-4 p-4"><h5>Yağ</h5><h2 class="fw-bold mb-0"><span id="toplamYag">0</span>g</h2><div class="progress mt-3 bg-white bg-opacity-25" style="height:6px"><div id="barYag" class="progress-bar bg-white" style="width:0%"></div></div></div></div>
    </div>

    <div class="row mb-5">
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card border-0 shadow-lg rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3"><h6 class="fw-bold mb-0 text-muted">🍩 MAKRO DAĞILIMI</h6></div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div id="grafikYok" class="text-center text-muted" style="display:none;"><p>Veri Yok</p></div>
                    <canvas id="makroGrafigi" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between">
                    <h6 class="fw-bold mb-0 text-muted">📋 TÜKETİM LİSTESİ</h6>
                    <small class="text-muted" id="urunSayisi">0 Ürün</small>
                </div>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light sticky-top"><tr><th class="ps-4">Ürün</th><th class="text-center">Kalori</th><th class="d-none d-md-table-cell text-center">Makrolar</th><th class="text-end pe-4">Sil</th></tr></thead>
                        <tbody id="analizListesi"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. VERİLERİ ÇEK
    let phpVerisi = <?php echo !empty($gidalar) ? json_encode($gidalar, JSON_UNESCAPED_UNICODE) : '[]'; ?>;
    let localVerisi = JSON.parse(localStorage.getItem('misafir_analizleri')) || [];

    // Header.php'deki UYE_GIRIS_YAPTI'yı kullanıyoruz
    // Eğer üye ise PHP verisi, değilse LocalStorage verisi
    let aktifVeri = (typeof UYE_GIRIS_YAPTI !== 'undefined' && UYE_GIRIS_YAPTI) ? phpVerisi : localVerisi;

    // 2. HESAPLA VE ÇİZ
    let tKalori = 0, tProtein = 0, tYag = 0, tKarb = 0;
    const listeTablo = document.getElementById('analizListesi');
    listeTablo.innerHTML = '';

    if (aktifVeri.length === 0) {
        listeTablo.innerHTML = '<tr><td colspan="4" class="text-center py-5 text-muted">Listeniz boş.</td></tr>';
        document.getElementById('grafikYok').style.display = 'block';
    } else {
        document.getElementById('grafikYok').style.display = 'none';

        aktifVeri.forEach((item) => {
            let kalori = parseFloat(item.kalori || item.kalori_100gr || 0);
            let protein = parseFloat(item.protein || 0);
            let yag = parseFloat(item.yag || 0);
            let karb = parseFloat(item.karb || 0);
            
            tKalori += kalori; tProtein += protein; tYag += yag; tKarb += karb;

            let resim = item.resim || item.resim_url || "https://placehold.co/50";

            listeTablo.innerHTML += `
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <img src="${resim}" class="rounded-3 border me-3" style="width: 50px; height: 50px; object-fit: contain;">
                            <div>
                                <div class="fw-bold text-dark text-truncate" style="max-width: 150px;">${item.urun_adi}</div>
                                <small class="text-muted">${item.marka || '-'}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center fw-bold">${kalori}</td>
                    <td class="text-center d-none d-md-table-cell">
                        <span class="badge bg-primary bg-opacity-10 text-primary">P: ${protein}g</span>
                        <span class="badge bg-warning bg-opacity-10 text-dark">K: ${karb}g</span>
                        <span class="badge bg-danger bg-opacity-10 text-danger">Y: ${yag}g</span>
                    </td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-light text-danger border" onclick="sil(${item.id}, '${(typeof UYE_GIRIS_YAPTI !== 'undefined' && UYE_GIRIS_YAPTI) ? 'db' : 'local'}')">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }

    // DOM GÜNCELLE
    document.getElementById('toplamKalori').innerText = Math.round(tKalori);
    document.getElementById('toplamProtein').innerText = tProtein.toFixed(1);
    document.getElementById('toplamKarb').innerText = tKarb.toFixed(1);
    document.getElementById('toplamYag').innerText = tYag.toFixed(1);
    document.getElementById('urunSayisi').innerText = aktifVeri.length + " Ürün";

    // GRAFİK
    if (aktifVeri.length > 0) {
        new Chart(document.getElementById('makroGrafigi'), {
            type: 'doughnut',
            data: {
                labels: ['Protein', 'Karb', 'Yağ'],
                datasets: [{ data: [tProtein, tKarb, tYag], backgroundColor: ['#0d6efd', '#ffc107', '#dc3545'], borderWidth: 0 }]
            },
            options: { cutout: '70%', plugins: { legend: { display: false } } }
        });
    }

    // SİLME
    function sil(id, kaynak) {
        if (!confirm('Silmek istediğine emin misin?')) return;
        if(kaynak === 'local') {
            let l = JSON.parse(localStorage.getItem('misafir_analizleri')) || [];
            localStorage.setItem('misafir_analizleri', JSON.stringify(l.filter(u => u.id !== id)));
            location.reload();
        } else {
            fetch('islemler/sil.php', { method: 'POST', headers: {'Content-Type':'application/x-www-form-urlencoded'}, body: 'id='+id })
            .then(r=>r.json()).then(d=>{ if(d.durum==='basarili') location.reload(); });
        }
    }

    function listeyiTemizle() {
        if(confirm('Temizlensin mi?')) {
            if (!(typeof UYE_GIRIS_YAPTI !== 'undefined' && UYE_GIRIS_YAPTI)) localStorage.removeItem('misafir_analizleri');
            location.reload();
        }
    }
</script>

<?php require_once 'includes/footer.php'; ?>