<?php 
require_once '../includes/db.php';
require_once '../includes/header.php'; 
?>

<style>
    /* Slider Stilleri */
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
        background: #0d6efd; /* Primary Mavi */
        cursor: pointer;
        border: 4px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    /* Sağ Taraf Sonuç Kartları (İstediğin Tasarım) */
    .vki-card {
        border-left: 5px solid #ccc; /* Varsayılan gri kenarlık */
        transition: all 0.3s ease;
        opacity: 0.7; /* Pasifken biraz soluk */
        border-radius: 12px;
        overflow: hidden;
        background-color: #f8f9fa; /* Hafif gri arka plan */
    }
    .vki-card .card-body {
        padding: 1.5rem;
    }
    
    /* Aktif Kart Stili (Parlayan) */
    .vki-card.active {
        opacity: 1;
        transform: scale(1.03);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        background-color: #fff; /* Aktifken beyaz arka plan */
    }

    /* Renk Tanımları ve Aktif Durum Kenarlıkları */
    .text-blue { color: #3b82f6; }
    .text-green { color: #10b981; }
    .text-yellow { color: #f59e0b; }
    .text-red { color: #ef4444; }

    /* Aktif olduğunda sol kenarlık rengini değiştir */
    .vki-card.active.border-blue { border-left-color: #3b82f6 !important; }
    .vki-card.active.border-green { border-left-color: #10b981 !important; }
    .vki-card.active.border-yellow { border-left-color: #f59e0b !important; }
    .vki-card.active.border-red { border-left-color: #ef4444 !important; }
</style>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Vücut Kitle İndeksi (BMI)</h2>
        <p class="text-muted">Değerlerini gir, sağlık durumunu öğren.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-3 d-none d-md-block">
            <div class="list-group shadow-sm border-0 sticky-top" style="top: 100px;">
                <a href="vki.php" class="list-group-item list-group-item-action active py-3 fw-bold border-0 d-flex align-items-center">
                    <i class="fa-solid fa-weight-scale me-3 fa-lg"></i> Vücut Kitle İndeksi (BMI)
                </a>
                <a href="kalori.php" class="list-group-item list-group-item-action py-3 border-0 d-flex align-items-center text-dark">
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
                            <h4 class="fw-bold mb-4">Bilgilerini Gir</h4>
                            
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
                                    <label class="fw-bold text-muted small">BOY</label>
                                    <span class="fw-bold fs-4 text-primary"><span id="val-boy">175</span> <small class="fs-6 text-muted">cm</small></span>
                                </div>
                                <input type="range" class="custom-range" id="range-boy" min="100" max="220" value="175" oninput="guncelle('boy', this.value)">
                            </div>

                            <div class="mb-5">
                                <div class="d-flex justify-content-between align-items-end mb-2">
                                    <label class="fw-bold text-muted small">KİLO</label>
                                    <span class="fw-bold fs-4 text-primary"><span id="val-kilo">75</span> <small class="fs-6 text-muted">kg</small></span>
                                </div>
                                <input type="range" class="custom-range" id="range-kilo" min="30" max="180" value="75" oninput="guncelle('kilo', this.value)">
                            </div>

                            <button class="btn btn-dark w-100 py-3 rounded-3 fw-bold fs-5 shadow-sm" onclick="hesapla()">HESAPLA</button>
                        </div>

                        <div class="col-lg-6 p-5 bg-light border-start">
                            <h4 class="fw-bold mb-4">VKI Kategorileri</h4>
                            
                            <div id="kat-zayif" class="card vki-card border-blue mb-3 border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="fw-bold text-blue mb-2">Zayıf (< 18.5)</h6>
                                    <p class="small text-muted mb-0">İdeal kilonun altında olabilirsiniz. Dengeli beslenme ve sağlıklı kilo alma önemlidir.</p>
                                </div>
                            </div>

                            <div id="kat-normal" class="card vki-card border-green mb-3 border-0 shadow-sm active">
                                <div class="card-body">
                                    <h6 class="fw-bold text-green mb-2">Normal (18.5 - 24.9)</h6>
                                    <p class="small text-muted mb-0">Tebrikler! İdeal kilo aralığındasınız. Sağlıklı yaşam tarzınızı koruyun.</p>
                                </div>
                            </div>

                            <div id="kat-kilolu" class="card vki-card border-yellow mb-3 border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="fw-bold text-yellow mb-2">Fazla Kilolu (25 - 29.9)</h6>
                                    <p class="small text-muted mb-0">İdeal kilonun üzerindesiniz. Dengeli beslenme ve düzenli egzersiz önerilir.</p>
                                </div>
                            </div>

                            <div id="kat-obez" class="card vki-card border-red border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="fw-bold text-red mb-2">Obez (≥ 30)</h6>
                                    <p class="small text-muted mb-0">Sağlık riskleri artmış durumda. Bir sağlık profesyonelinden destek almanız önerilir.</p>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <span class="text-muted small">Sizin VKI Değeriniz:</span>
                                <h1 class="display-4 fw-bold text-dark mt-1" id="sonucDeger">--</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Slider değerlerini anlık güncelle
    function guncelle(tur, deger) { document.getElementById('val-' + tur).innerText = deger; }
    
    // Hesaplama Fonksiyonu
    function hesapla() {
        const boy = parseFloat(document.getElementById('range-boy').value);
        const kilo = parseFloat(document.getElementById('range-kilo').value);
        
        // VKI Hesabı: Kilo / (Boy(m) * Boy(m))
        const vki = (kilo / ((boy/100) * (boy/100))).toFixed(1);
        
        // Değeri yazdır
        document.getElementById('sonucDeger').innerText = vki;
        
        // Tüm kartları pasif yap
        document.querySelectorAll('.vki-card').forEach(el => el.classList.remove('active'));
        
        // İlgili kartı aktif yap
        if (vki < 18.5) {
            aktifYap('kat-zayif');
        } else if (vki >= 18.5 && vki < 25) {
            aktifYap('kat-normal');
        } else if (vki >= 25 && vki < 30) {
            aktifYap('kat-kilolu');
        } else {
            aktifYap('kat-obez');
        }
    }
    
    // Kartı aktif yapma fonksiyonu
    function aktifYap(id) { document.getElementById(id).classList.add('active'); }
    
    // Sayfa yüklendiğinde bir kere hesapla
    document.addEventListener("DOMContentLoaded", hesapla);
</script>

<?php require_once '../includes/footer.php'; ?>