<?php 
require_once '../includes/db.php';
require_once '../includes/header.php'; 
?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h6 class="text-primary fw-bold text-uppercase ls-2">HESAPLAMA ARAÇLARI</h6>
        <h2 class="fw-bold display-5">Vücudunu Tanı, Kontrolü Al</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">
            Bilimsel formüllerle hazırladığımız araçları kullanarak ideal kilonu, kalori ihtiyacını ve sağlık durumunu ücretsiz öğren.
        </p>
    </div>

    <div class="row g-4 justify-content-center">
        
        <div class="col-md-6 col-lg-4 col-xl-3"> 
            <a href="vki.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all text-center p-4">
                    <div class="card-body">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                            <i class="fa-solid fa-weight-scale fa-2x"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Vücut Kitle İndeksi</h5>
                        <p class="text-muted small mb-4">Boy ve kilonuza göre sağlık durumunuzu analiz edin.</p>
                        <span class="btn btn-sm btn-outline-primary rounded-pill px-4 fw-bold">Hesapla <i class="fa-solid fa-arrow-right ms-2"></i></span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-3">
            <a href="kalori.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all text-center p-4">
                    <div class="card-body">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning mx-auto rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                            <i class="fa-solid fa-fire fa-2x"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Kalori İhtiyacı</h5>
                        <p class="text-muted small mb-4">Hedefine ulaşmak için günde kaç kalori tüketmelisin?</p>
                        <span class="btn btn-sm btn-outline-warning text-dark rounded-pill px-4 fw-bold">Hesapla <i class="fa-solid fa-arrow-right ms-2"></i></span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-3">
            <a href="ideal.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all text-center p-4">
                    <div class="card-body">
                        <div class="icon-box bg-info bg-opacity-10 text-info mx-auto rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                            <i class="fa-solid fa-ruler-vertical fa-2x"></i>
                        </div>
                        <h5 class="fw-bold text-dark">İdeal Kilo</h5>
                        <p class="text-muted small mb-4">Olman gereken en sağlıklı kilo aralığını öğren.</p>
                        <span class="btn btn-sm btn-outline-info text-dark rounded-pill px-4 fw-bold">Hesapla <i class="fa-solid fa-arrow-right ms-2"></i></span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-3">
            <a href="yag_orani.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all text-center p-4">
                    <div class="card-body">
                        <div class="icon-box bg-danger bg-opacity-10 text-danger mx-auto rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                            <i class="fa-solid fa-percent fa-2x"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Vücut Yağ Oranı</h5>
                        <p class="text-muted small mb-4">Mezura ölçülerinle yağ oranını (US Navy) hesapla.</p>
                        <span class="btn btn-sm btn-outline-danger text-dark rounded-pill px-4 fw-bold">Hesapla <i class="fa-solid fa-arrow-right ms-2"></i></span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4 col-xl-3">
            <a href="su_ihtiyaci.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all text-center p-4">
                    <div class="card-body">
                        <div class="icon-box bg-success bg-opacity-10 text-success mx-auto rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                            <i class="fa-solid fa-glass-water fa-2x"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Günlük Su İhtiyacı</h5>
                        <p class="text-muted small mb-4">Günde kaç bardak su içmen gerektiğini hemen öğren.</p>
                        <span class="btn btn-sm btn-outline-success text-dark rounded-pill px-4 fw-bold">Hesapla <i class="fa-solid fa-arrow-right ms-2"></i></span>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    .transition-all { transition: all 0.3s ease; }
</style>

<?php require_once '../includes/footer.php'; ?>