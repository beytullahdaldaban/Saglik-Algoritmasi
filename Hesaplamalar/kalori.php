<?php 
require_once '../includes/db.php';
require_once '../includes/header.php'; 
?>

<style>
    .custom-range {
        width: 100%;
        height: 8px;
        border-radius: 5px;
        background: #e9ecef;
        outline: none;
        -webkit-appearance: none;
    }
    .custom-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: #ffc107;
        cursor: pointer;
        border: 4px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .sonuc-kutu {
        transition: all 0.3s ease;
        border-left: 5px solid transparent;
    }
    .sonuc-kutu:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .b-warning { border-color: #ffc107 !important; }
    .b-success { border-color: #198754 !important; }
    .b-danger { border-color: #dc3545 !important; }
</style>

<div class="container py-5">
    
    <div class="text-center mb-5">
        <h2 class="fw-bold">Kalori Hesaplama Aracı</h2>
        <p class="text-muted">Hedefine ulaşmak için günde kaç kalori yemelisin?</p>
    </div>

    <div class="row g-4">
        <div class="col-md-3 d-none d-md-block">
            <div class="list-group shadow-sm border-0 sticky-top" style="top: 100px;">
                <a href="vki.php" class="list-group-item list-group-item-action py-3 border-0 d-flex align-items-center text-dark">
                    <i class="fa-solid fa-weight-scale me-3 fa-lg"></i> Vücut Kitle İndeksi (BMI)
                </a>
                <a href="kalori.php" class="list-group-item list-group-item-action active py-3 fw-bold border-0 d-flex align-items-center">
                    <i class="fa-solid fa-fire me-3 fa-lg"></i> Günlük Kalori İhtiyacı
                </a>
                <a href="ideal.php" class="list-group-item list-group-item-action py-3 border-0 d-flex align-items-center text-dark">
                    <i class="fa-solid fa-ruler-vertical me-3 fa-lg"></i> İdeal Kilo
                </a>
                <a href="yag_orani.php" class="list-group-item list-group-item-action py-3 border-0 d-flex align-items-center text-dark">
                    <i class="fa-solid fa-percent me-3 fa-lg"></i> Vücut Yağ Oranı
                </a>
                <a href="su_ihtiyaci.php" class="list-group-item list-group-item-action py-3 border-0 d-flex align-items-center text-dark">
                    <i class="fa-solid fa-glass-water me-3 fa-lg"></i> Günlük Su İhtiyacı
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="row g-0">
                        
                        <div class="col-lg-6 p-5 bg-white">
                            <h4 class="fw-bold mb-4">Verilerini Gir</h4>
                            
                            <div class="mb-4">
                                <label class="fw-bold text-muted small mb-2">CİNSİYET</label>
                                <div class="d-flex gap-3">
                                    <input type="radio" class="btn-check" name="cinsiyet" id="erkek" checked>
                                    <label class="btn btn-outline-dark w-100 py-2 rounded-3" for="erkek">Erkek</label>
                                    <input type="radio" class="btn-check" name="cinsiyet" id="kadin">
                                    <label class="btn btn-outline-dark w-100 py-2 rounded-3" for="kadin">Kadın</label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-end mb-2">
                                    <label class="fw-bold text-muted small">YAŞ</label>
                                    <span class="fw-bold fs-4 text-warning"><span id="val-yas">25</span></span>
                                </div>
                                <input type="range" class="custom-range" id="range-yas" min="10" max="90" value="25" oninput="guncelle('yas', this.value)">
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-end mb-2">
                                    <label class="fw-bold text-muted small">BOY</label>
                                    <span class="fw-bold fs-4 text-warning"><span id="val-boy">175</span> <small class="fs-6 text-muted">cm</small></span>
                                </div>
                                <input type="range" class="custom-range" id="range-boy" min="100" max="220" value="175" oninput="guncelle('boy', this.value)">
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-end mb-2">
                                    <label class="fw-bold text-muted small">KİLO</label>
                                    <span class="fw-bold fs-4 text-warning"><span id="val-kilo">75</span> <small class="fs-6 text-muted">kg</small></span>
                                </div>
                                <input type="range" class="custom-range" id="range-kilo" min="30" max="180" value="75" oninput="guncelle('kilo', this.value)">
                            </div>

                            <div class="mb-4">
                                <label class="fw-bold text-muted small mb-2">HAREKET SEVİYESİ</label>
                                <select class="form-select form-select-lg shadow-none" id="aktivite">
                                    <option value="1.2">Masa Başı (Hareketsiz)</option>
                                    <option value="1.375">Az Hareketli (Haftada 1-3 gün spor)</option>
                                    <option value="1.55" selected>Orta Hareketli (Haftada 3-5 gün spor)</option>
                                    <option value="1.725">Çok Hareketli (Haftada 6-7 gün spor)</option>
                                    <option value="1.9">Profesyonel Sporcu</option>
                                </select>
                            </div>

                            <button class="btn btn-dark w-100 py-3 rounded-3 fw-bold fs-5 shadow-sm" onclick="hesaplaKalori()">HESAPLA</button>
                        </div>

                        <div class="col-lg-6 p-5 bg-light border-start d-flex flex-column justify-content-center">
                            
                            <div class="text-center mb-5">
                                <h6 class="text-uppercase text-muted ls-2 fw-bold">Günlük İhtiyacın</h6>
                                <h1 class="display-3 fw-bold text-dark mb-0" id="sonucMevcut">--</h1>
                                <small class="text-muted d-block">Mevcut kilonu korumak için.</small>
                                <button class="btn btn-sm btn-outline-dark rounded-pill mt-3 px-4 shadow-sm fw-bold" onclick="hedefiGuncelle('sonucMevcut')">
                                    <i class="fa-solid fa-check me-1"></i> Bunu Hedef Yap
                                </button>
                            </div>

                            <div class="card sonuc-kutu b-success shadow-sm mb-3 border-0">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="fw-bold text-success mb-0">Kilo Vermek İçin</h6>
                                        <small class="text-muted">Sağlıklı tempoda</small>
                                    </div>
                                    <div class="text-end">
                                        <h3 class="fw-bold text-success mb-0" id="sonucVerme">--</h3>
                                        <button class="btn btn-sm btn-outline-success rounded-pill mt-1 py-0" onclick="hedefiGuncelle('sonucVerme')">Seç</button>
                                    </div>
                                </div>
                            </div>

                            <div class="card sonuc-kutu b-danger shadow-sm border-0">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="fw-bold text-danger mb-0">Kilo Almak İçin</h6>
                                        <small class="text-muted">Kas kazanımı</small>
                                    </div>
                                    <div class="text-end">
                                        <h3 class="fw-bold text-danger mb-0" id="sonucAlma">--</h3>
                                        <button class="btn btn-sm btn-outline-danger rounded-pill mt-1 py-0" onclick="hedefiGuncelle('sonucAlma')">Seç</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function guncelle(tur, deger) { document.getElementById('val-' + tur).innerText = deger; }

    function hesaplaKalori() {
        const cinsiyet = document.getElementById('erkek').checked ? 'erkek' : 'kadin';
        const yas = parseInt(document.getElementById('range-yas').value);
        const boy = parseInt(document.getElementById('range-boy').value);
        const kilo = parseInt(document.getElementById('range-kilo').value);
        const aktivite = parseFloat(document.getElementById('aktivite').value);

        let bmr = (10 * kilo) + (6.25 * boy) - (5 * yas);
        if (cinsiyet === 'erkek') { bmr += 5; } else { bmr -= 161; }
        const tdee = Math.round(bmr * aktivite);

        document.getElementById('sonucMevcut').innerText = tdee + " kcal";
        document.getElementById('sonucVerme').innerText = (tdee - 500) + " kcal";
        document.getElementById('sonucAlma').innerText = (tdee + 400) + " kcal";
    }

    // --- YENİ EKLENEN HEDEF GÜNCELLEME FONKSİYONU ---
    function hedefiGuncelle(elementId) {
        // Seçilen kutudaki değeri al (Örn: "2500 kcal" -> 2500 yap)
        let metin = document.getElementById(elementId).innerText;
        let kalori = parseInt(metin);

        if (isNaN(kalori)) {
            Swal.fire('Hata', 'Lütfen önce hesaplama yapın.', 'warning');
            return;
        }

        // AJAX ile gönder (hedef_guncelle.php dosyasının yoluna dikkat et)
        fetch('../islemler/hedef_guncelle.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ hedef: kalori })
        })
        .then(response => response.json())
        .then(data => {
            if (data.durum === 'basarili') {
                Swal.fire({
                    icon: 'success',
                    title: 'Hedef Güncellendi!',
                    text: `Günlük hedefin ${kalori} kcal olarak ayarlandı.`,
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                Swal.fire('Uyarı', data.mesaj, 'error');
            }
        })
        .catch(error => {
            console.error('Hata:', error);
            Swal.fire('Hata', 'İşlem sırasında bir sorun oluştu.', 'error');
        });
    }

    document.addEventListener("DOMContentLoaded", hesaplaKalori);
</script>

<?php require_once '../includes/footer.php'; ?>