<?php require_once 'includes/header.php'; ?>

<header class="hero-section text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <span class="badge bg-white text-primary px-3 py-2 rounded-pill mb-3 shadow-sm">
                    🚀 Sağlıklı Yaşamın Başlangıç Noktası
                </span>
                <h1 class="hero-title">Ne Yediğinin <br> Farkında Mısın?</h1>
                <p class="lead text-white-50 mb-5 fs-4">
                    Market raflarındaki gizli şekeri keşfet, kalorileri egzersize dönüştür ve kontrolü eline al.
                </p>

                <div class="glass-search mx-auto" style="max-width: 700px;">
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-transparent text-white ms-2">
                            <i class="fa-solid fa-magnifying-glass fa-lg"></i>
                        </span>
                        <input type="text" id="aramaKutusu" class="form-control form-control-lg" placeholder="Merak ettiğin ürünü yaz (Örn: Nutella)...">
                        <button class="btn btn-search" type="button" onclick="gidaAra()">
                            İNCELE
                        </button>
                    </div>
                </div>
                
                <div class="mt-3 text-white-50 small">
                    <i class="fa-solid fa-database"></i> 3.000.000+ Ürün Veritabanı
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container mt-4 mb-5">
    <div id="sonucAlani" class="row justify-content-center"></div>
</div>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase ls-2">NEDEN SAĞLIK ALGORİTMASI?</h6>
            <h2 class="fw-bold display-6">Bilinçli Tüketim İçin 3 Neden</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="icon-box" style="background: linear-gradient(45deg, #ff9a9e 0%, #fad0c4 99%);">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>
                    <h4 class="fw-bold">Gizli Tehlike</h4>
                    <p class="text-muted">Paketli gıdalardaki fruktoz şurubu ve gizli şekerler, diyabet riskini %40 artırır. Biz bunları ortaya çıkarıyoruz.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="icon-box" style="background: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);">
                        <i class="fa-solid fa-person-running"></i>
                    </div>
                    <h4 class="fw-bold">Kalori Dönüştürücü</h4>
                    <p class="text-muted">"Bir çikolata kaç dakika koşuya bedel?" sorusunun cevabını anında veriyoruz. Harekete geçmen için bir sebep.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="icon-box" style="background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);">
                        <i class="fa-solid fa-barcode"></i>
                    </div>
                    <h4 class="fw-bold">Hızlı Analiz</h4>
                    <p class="text-muted">Binlerce ürün arasından anında arama yapın. Barkod veya isimle saniyeler içinde besin değerlerine ulaşın.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 my-5 container">
    <div class="bg-dark text-white rounded-5 p-5 position-relative overflow-hidden shadow-lg">
        <div class="position-absolute top-0 end-0 p-5 opacity-10">
            <i class="fa-solid fa-carrot fa-10x"></i>
        </div>
        
        <div class="row align-items-center position-relative z-1">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-3">Daha Sağlıklı Bir Yaşam Seni Bekliyor!</h2>
                <p class="lead text-white-50">Forumda sorular sor, blog yazılarını oku ve gelişimini takip et. Hemen aramıza katıl.</p>
            </div>
            
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <?php if(!isset($_SESSION['uye_id'])): ?>
                    <a href="uyelik/kayit.php" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold text-dark shadow">
                        <i class="fa-solid fa-user-plus"></i> ÜYE OL
                    </a>
                <?php else: ?>
                    <a href="takip.php" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold text-white shadow">
                        <i class="fa-solid fa-chart-line"></i> TAKİBE BAŞLA
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/reklam.php'; ?>

<?php require_once 'includes/footer.php'; ?>