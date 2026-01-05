<?php 
require_once '../includes/db.php';
require_once '../includes/header.php'; 
?>

<style>
    /* Slider ve Renkler */
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
        background: #dc3545; /* MacFit Kırmızısı */
        cursor: pointer;
        border: 4px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    /* Sağ Taraf Sonuç Alanı */
    .sonuc-kart {
        background: #fff;
        border-radius: 12px;
        border-left: 5px solid #dc3545;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    
    .gizli-alan {
        display: none;
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    .gizli-alan.aktif {
        display: block;
        opacity: 1;
    }
    .text-red { color: #dc3545; }
</style>

<div class="container py-5">
    
    <div class="text-center mb-5">
        <h2 class="fw-bold">Vücut Yağ Oranı</h2>
        <p class="text-muted">Mezura ölçülerinle yağ oranını öğren.</p>
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
                <a href="ideal.php" class="list-group-item list-group-item-action py-3 border-0 d-flex align-items-center text-dark">
                    <i class="fa-solid fa-ruler-vertical me-3 fa-lg"></i> İdeal Kilo
                </a>
                <a href="yag_orani.php" class="list-group-item list-group-item-action active py-3 fw-bold border-0 d-flex align-items-center">
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
                            <h4 class="fw-bold mb-4">Ölçülerini Gir</h4>
                            
                            <div class="mb-4">
                                <label class="fw-bold text-muted small mb-2">CİNSİYET</label>
                                <div class="d-flex gap-3">
                                    <input type="radio" class="btn-check" name="cinsiyet" id="erkek" checked onchange="cinsiyetDegisti()">
                                    <label class="btn btn-outline-dark w-100 py-2 rounded-3" for="erkek">Erkek</label>

                                    <input type="radio" class="btn-check" name="cinsiyet" id="kadin" onchange="cinsiyetDegisti()">
                                    <label class="btn btn-outline-dark w-100 py-2 rounded-3" for="kadin">Kadın</label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-end mb-2">
                                    <label class="fw-bold text-muted small">BOY (cm)</label>
                                    <span class="fw-bold fs-4 text-red"><span id="val-boy">175</span></span>
                                </div>
                                <input type="range" class="custom-range" id="range-boy" min="140" max="220" value="175" oninput="guncelle('boy', this.value)">
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-end mb-2">
                                    <label class="fw-bold text-muted small">BOYUN ÇEVRESİ (cm)</label>
                                    <span class="fw-bold fs-4 text-red"><span id="val-boyun">38</span></span>
                                </div>
                                <input type="range" class="custom-range" id="range-boyun" min="20" max="60" value="38" oninput="guncelle('boyun', this.value)">
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-end mb-2">
                                    <label class="fw-bold text-muted small">BEL ÇEVRESİ (cm)</label>
                                    <span class="fw-bold fs-4 text-red"><span id="val-bel">85</span></span>
                                </div>
                                <input type="range" class="custom-range" id="range-bel" min="40" max="150" value="85" oninput="guncelle('bel', this.value)">
                            </div>

                            <div class="mb-4 gizli-alan" id="kalca-container">
                                <div class="d-flex justify-content-between align-items-end mb-2">
                                    <label class="fw-bold text-muted small">KALÇA ÇEVRESİ (cm)</label>
                                    <span class="fw-bold fs-4 text-red"><span id="val-kalca">95</span></span>
                                </div>
                                <input type="range" class="custom-range" id="range-kalca" min="40" max="150" value="95" oninput="guncelle('kalca', this.value)">
                            </div>

                            <button class="btn btn-dark w-100 py-3 rounded-3 fw-bold fs-5 shadow-sm mt-3" onclick="hesaplaYag()">HESAPLA</button>
                        </div>

                        <div class="col-lg-6 p-5 bg-light border-start d-flex flex-column justify-content-center">
                            
                            <div class="text-center mb-5">
                                <h6 class="text-uppercase text-muted ls-2 fw-bold">Vücut Yağ Oranı</h6>
                                <h1 class="display-1 fw-bold text-dark mb-0">%<span id="sonucYag">--</span></h1>
                                <span class="badge bg-danger px-4 py-2 mt-3 rounded-pill fs-6" id="sonucDurum">--</span>
                            </div>

                            <div class="sonuc-kart p-4">
                                <div class="d-flex align-items-start">
                                    <i class="fa-solid fa-circle-info text-danger mt-1 me-3 fs-5"></i>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Ne Anlama Geliyor?</h6>
                                        <p class="small text-muted mb-0">Bu oran, vücudundaki toplam yağ dokusunun yüzdesidir. Erkekler için %10-20, kadınlar için %18-28 arası ideal kabul edilir.</p>
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
    function guncelle(tur, deger) {
        document.getElementById('val-' + tur).innerText = deger;
    }

    function cinsiyetDegisti() {
        const kadin = document.getElementById('kadin').checked;
        const kalcaKutu = document.getElementById('kalca-container');
        
        if (kadin) {
            kalcaKutu.classList.add('aktif');
        } else {
            kalcaKutu.classList.remove('aktif');
        }
    }

    function hesaplaYag() {
        const cinsiyet = document.getElementById('erkek').checked ? 'erkek' : 'kadin';
        const boy = parseInt(document.getElementById('range-boy').value);
        const boyun = parseInt(document.getElementById('range-boyun').value);
        const bel = parseInt(document.getElementById('range-bel').value);
        const kalca = parseInt(document.getElementById('range-kalca').value);
        
        let yagOrani = 0;

        // US Navy Formülü (Kilosuz)
        if (cinsiyet === 'erkek') {
            // Erkek Formülü: 495 / (1.0324 - 0.19077(log(bel-boyun)) + 0.15456(log(boy))) - 450
            if(bel <= boyun) {
                 // Hata koruması: Bel boyundan küçük olamaz bu formülde
                 yagOrani = 5; 
            } else {
                yagOrani = 495 / (1.0324 - 0.19077 * Math.log10(bel - boyun) + 0.15456 * Math.log10(boy)) - 450;
            }
        } else {
            // Kadın Formülü: 495 / (1.29579 - 0.35004(log(bel+kalca-boyun)) + 0.22100(log(boy))) - 450
             if((bel + kalca) <= boyun) {
                 yagOrani = 10;
             } else {
                yagOrani = 495 / (1.29579 - 0.35004 * Math.log10(bel + kalca - boyun) + 0.22100 * Math.log10(boy)) - 450;
             }
        }

        // Sonucu Yuvarla (Negatif veya çok düşük çıkarsa en az %2 yap)
        yagOrani = Math.round(yagOrani * 10) / 10;
        if(yagOrani < 2) yagOrani = 2;

        // Kategori Belirle
        let durum = "";
        if (cinsiyet === 'erkek') {
            if (yagOrani < 6) durum = "Hayati Yağ";
            else if (yagOrani < 14) durum = "Atletik";
            else if (yagOrani < 18) durum = "Fit";
            else if (yagOrani < 25) durum = "Normal";
            else durum = "Obez";
        } else {
            if (yagOrani < 14) durum = "Hayati Yağ";
            else if (yagOrani < 21) durum = "Atletik";
            else if (yagOrani < 25) durum = "Fit";
            else if (yagOrani < 32) durum = "Normal";
            else durum = "Obez";
        }

        // Ekrana Yaz
        document.getElementById('sonucYag').innerText = yagOrani;
        document.getElementById('sonucDurum').innerText = durum;
    }

    // Sayfa açılınca varsayılan bir hesap yapsın (Boş durmasın)
    document.addEventListener("DOMContentLoaded", hesaplaYag);
</script>

<?php require_once '../includes/footer.php'; ?>