<?php 
require_once '../includes/db.php';
require_once '../includes/header.php'; 
?>

<style>
    /* Slider ve Ortak Stiller */
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
        background: #0dcaf0; /* İdeal Kilo için Cyan/Turkuaz renk */
        cursor: pointer;
        border: 4px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    /* Sonuç Kartları */
    .sonuc-kart {
        background: #fff;
        border-radius: 12px;
        border-left: 5px solid #0dcaf0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }
    .sonuc-kart:hover {
        transform: translateY(-5px);
    }
</style>

<div class="container py-5">
    
    <div class="text-center mb-5">
        <h2 class="fw-bold">İdeal Kilo Hesaplama</h2>
        <p class="text-muted">Boyuna ve yapına göre olman gereken en sağlıklı kilo.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-3 d-none d-md-block">
            <div class="list-group shadow-sm border-0 sticky-top" style="top: 100px;">
                <a href="vki.php" class="list-group-item list-group-item-action py-3 border-0 d-flex align-items-center text-dark">
                    <i class="fa-solid fa-weight-scale me-3 fa-lg"></i> Vücut Kitle İndeksi (BMI)
                </a>
                <a href="kalori.php" class="list-group-item list-group-item-action py-3 border-0 d-flex align-items-center text-dark">
                    <i class="fa-solid fa-fire me-3 fa-lg"></i> Günlük Kalori İhtiyacı
                </a>
                <a href="ideal.php" class="list-group-item list-group-item-action active py-3 fw-bold border-0 d-flex align-items-center">
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
                                    <input type="radio" class="btn-check" name="cinsiyet" id="erkek" checked onchange="hesapla()">
                                    <label class="btn btn-outline-dark w-100 py-2 rounded-3" for="erkek">Erkek</label>

                                    <input type="radio" class="btn-check" name="cinsiyet" id="kadin" onchange="hesapla()">
                                    <label class="btn btn-outline-dark w-100 py-2 rounded-3" for="kadin">Kadın</label>
                                </div>
                            </div>

                            <div class="mb-5">
                                <div class="d-flex justify-content-between align-items-end mb-2">
                                    <label class="fw-bold text-muted small">BOY</label>
                                    <span class="fw-bold fs-4 text-info"><span id="val-boy">175</span> <small class="fs-6 text-muted">cm</small></span>
                                </div>
                                <input type="range" class="custom-range" id="range-boy" min="140" max="220" value="175" oninput="guncelle('boy', this.value)">
                            </div>

                            <div class="alert alert-light border text-muted small">
                                <i class="fa-solid fa-circle-info text-info me-1"></i> 
                                İdeal kilo hesabı, Dr. B.J. Devine formülü ve Dünya Sağlık Örgütü standartlarına göre yapılmaktadır.
                            </div>
                        </div>

                        <div class="col-lg-6 p-5 bg-light border-start d-flex flex-column justify-content-center">
                            
                            <div class="text-center mb-5">
                                <h6 class="text-uppercase text-muted ls-2 fw-bold">İdeal Kilon</h6>
                                <h1 class="display-2 fw-bold text-dark mb-0" id="sonucHedef">--</h1>
                                <small class="text-muted">En fit olacağın nokta</small>
                            </div>

                            <div class="sonuc-kart p-4 mb-3" style="border-left-color: #198754;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold text-success mb-1">Sağlıklı Aralık</h6>
                                        <small class="text-muted d-block" style="font-size: 0.8rem;">Bu aralıkta olmak tıbben sağlıklıdır.</small>
                                    </div>
                                    <h4 class="fw-bold text-success mb-0" id="sonucAralik">--</h4>
                                </div>
                            </div>

                            <div class="sonuc-kart p-4" style="border-left-color: #ffc107;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Düşmemen Gereken</h6>
                                        <small class="text-muted">Alt sınır</small>
                                    </div>
                                    <h4 class="fw-bold text-dark mb-0" id="sonucMin">--</h4>
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
    function guncelle(tur, deger) {
        document.getElementById('val-' + tur).innerText = deger;
        hesapla();
    }

    function hesapla() {
        const cinsiyet = document.getElementById('erkek').checked ? 'erkek' : 'kadin';
        const boy = parseInt(document.getElementById('range-boy').value);

        // 1. Devine Formülü (Nokta atışı ideal kilo)
        // Erkek: 50 + 2.3 * ((Boy_inch - 60)) -> 1 inch = 2.54 cm
        // Kadın: 45.5 + 2.3 * ((Boy_inch - 60))
        
        let ideal = 0;
        let boyInch = boy / 2.54;

        if (cinsiyet === 'erkek') {
            ideal = 50 + 2.3 * (boyInch - 60);
        } else {
            ideal = 45.5 + 2.3 * (boyInch - 60);
        }

        // 2. Sağlıklı BMI Aralığı (18.5 - 24.9 arası)
        // Kilo = BMI * (Boy_metre * Boy_metre)
        let minKilo = 18.5 * ((boy/100) * (boy/100));
        let maxKilo = 24.9 * ((boy/100) * (boy/100));

        // Sonuçları Yazdır
        document.getElementById('sonucHedef').innerText = Math.round(ideal) + " kg";
        document.getElementById('sonucAralik').innerText = Math.round(minKilo) + " - " + Math.round(maxKilo) + " kg";
        document.getElementById('sonucMin').innerText = Math.round(minKilo) + " kg";
    }

    // Açılışta çalıştır
    document.addEventListener("DOMContentLoaded", hesapla);
</script>

<?php require_once '../includes/footer.php'; ?>