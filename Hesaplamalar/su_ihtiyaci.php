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
        background: #0dcaf0; /* Su Mavisi (Cyan) */
        cursor: pointer;
        border: 4px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    /* Sağ Taraf Sonuç Alanı */
    .sonuc-kart {
        background: #fff;
        border-radius: 12px;
        border-left: 5px solid #0dcaf0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }
    .sonuc-kart:hover { transform: translateY(-5px); }
    
    .text-cyan { color: #0dcaf0; }
</style>

<div class="container py-5">
    
    <div class="text-center mb-5">
        <h2 class="fw-bold">Günlük Su İhtiyacı</h2>
        <p class="text-muted">Vücudunun susuz kalmaması için ne kadar içmelisin?</p>
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
                <a href="yag_orani.php" class="list-group-item list-group-item-action py-3 border-0 d-flex align-items-center text-dark">
                    <i class="fa-solid fa-percent me-3 fa-lg"></i> Vücut Yağ Oranı
                </a>
                <a href="su_ihtiyaci.php" class="list-group-item list-group-item-action active py-3 fw-bold border-0 d-flex align-items-center">
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
                            
                            <div class="mb-5">
                                <div class="d-flex justify-content-between align-items-end mb-2">
                                    <label class="fw-bold text-muted small">KİLO (kg)</label>
                                    <span class="fw-bold fs-4 text-cyan"><span id="val-kilo">75</span></span>
                                </div>
                                <input type="range" class="custom-range" id="range-kilo" min="30" max="150" value="75" oninput="guncelle('kilo', this.value)">
                            </div>

                            <div class="mb-4">
                                <label class="fw-bold text-muted small mb-2">HAREKET SEVİYESİ</label>
                                <select class="form-select form-select-lg shadow-none" id="aktivite" onchange="hesaplaSu()">
                                    <option value="0">Hareketsiz (Ofis vb.)</option>
                                    <option value="350">Az Hareketli (30 dk yürüyüş)</option>
                                    <option value="700" selected>Aktif (1 saat spor)</option>
                                    <option value="1000">Çok Aktif (Ağır antrenman)</option>
                                </select>
                                <div class="form-text mt-2"><i class="fa-solid fa-circle-info me-1"></i> Terledikçe ihtiyacın artar.</div>
                            </div>

                            <button class="btn btn-dark w-100 py-3 rounded-3 fw-bold fs-5 shadow-sm mt-3" onclick="hesaplaSu()">HESAPLA</button>
                        </div>

                        <div class="col-lg-6 p-5 bg-light border-start d-flex flex-column justify-content-center">
                            
                            <div class="text-center mb-5">
                                <h6 class="text-uppercase text-muted ls-2 fw-bold">Günlük Hedefin</h6>
                                <h1 class="display-2 fw-bold text-dark mb-0"><span id="sonucLitre">--</span> <small class="fs-4 text-muted">lt</small></h1>
                                <span class="badge bg-info text-dark px-3 py-2 mt-2 rounded-pill" id="sonucMl">-- ml</span>
                            </div>

                            <div class="sonuc-kart p-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-glass-water fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">Bardak Sayısı</h6>
                                        <small class="text-muted">200ml standart bardak ile</small>
                                        <h4 class="fw-bold text-info mb-0 mt-1"><span id="sonucBardak">--</span> Bardak</h4>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="sonuc-kart p-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-bottle-water fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">Pet Şişe Sayısı</h6>
                                        <small class="text-muted">500ml şişe ile</small>
                                        <h4 class="fw-bold text-primary mb-0 mt-1"><span id="sonucSise">--</span> Şişe</h4>
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

    function hesaplaSu() {
        const kilo = parseInt(document.getElementById('range-kilo').value);
        const aktiviteEkstra = parseInt(document.getElementById('aktivite').value);
        
        // Temel İhtiyaç: Kilo başına 35ml
        let temelIhtiyac = kilo * 35;
        
        // Toplam İhtiyaç
        let toplamMl = temelIhtiyac + aktiviteEkstra;
        let toplamLt = (toplamMl / 1000).toFixed(2);
        
        // Bardak ve Şişe Hesabı
        let bardak = Math.ceil(toplamMl / 200);
        let sise = (toplamMl / 500).toFixed(1);

        // Yazdır
        document.getElementById('sonucLitre').innerText = toplamLt;
        document.getElementById('sonucMl').innerText = toplamMl + " ml";
        document.getElementById('sonucBardak').innerText = bardak;
        document.getElementById('sonucSise').innerText = sise;
    }

    // Açılışta çalıştır
    document.addEventListener("DOMContentLoaded", hesaplaSu);
</script>

<?php require_once '../includes/footer.php'; ?>