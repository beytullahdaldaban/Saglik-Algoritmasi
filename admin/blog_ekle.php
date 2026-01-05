<?php
session_start();
require_once '../includes/db.php';

// GÜVENLİK: Eğer giriş yapmamışsa veya rolü admin değilse ANA SAYFAYA AT!
if (!isset($_SESSION['uye_id']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$mesaj = "";

// Form Gönderildi mi?
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $baslik = htmlspecialchars($_POST['baslik']);
    $resim = htmlspecialchars($_POST['resim']);
    $icerik = $_POST['icerik']; 

    if(!empty($baslik) && !empty($icerik)) {
        // 1. ADIM: Otomatik Özet Oluştur (Veritabanında 'ozet' zorunlu olduğu için)
        // İçeriğin ilk 150 karakterini alıp sonuna ... ekler
        $ozet = mb_substr(strip_tags($icerik), 0, 150) . '...';

        // 2. ADIM: Veritabanına Ekle (SQLite Uyumlu)
        // NOW() fonksiyonu yerine datetime('now', 'localtime') kullandık.
        // Ayrıca 'ozet' sütununu da ekledik.
        try {
            $ekle = $db->prepare("INSERT INTO blog (baslik, ozet, icerik, resim, tarih) VALUES (?, ?, ?, ?, datetime('now', 'localtime'))");
            $sonuc = $ekle->execute([$baslik, $ozet, $icerik, $resim]);

            if($sonuc) {
                $mesaj = '<div class="alert alert-success shadow-sm"><i class="fa-solid fa-check-circle me-2"></i>Harika! Yazı başarıyla paylaşıldı patron.</div>';
            } else {
                $mesaj = '<div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i>Kayıt başarısız oldu.</div>';
            }
        } catch (PDOException $e) {
            $mesaj = '<div class="alert alert-danger"><i class="fa-solid fa-bug me-2"></i>Hata: ' . $e->getMessage() . '</div>';
        }

    } else {
        $mesaj = '<div class="alert alert-warning"><i class="fa-solid fa-circle-exclamation me-2"></i>Lütfen başlık ve içerik alanlarını doldur.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Blog Ekle - Yönetici Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="d-flex">
    <div class="bg-dark text-white p-3 d-none d-md-block" style="width: 280px; min-height: 100vh;">
        <h4 class="mb-4 text-center text-warning fw-bold"><i class="fa-solid fa-user-secret"></i> ADMİN</h4>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-2">
                <a href="index.php" class="nav-link text-white">
                    <i class="fa-solid fa-gauge me-2"></i> Kontrol Paneli
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="blog_ekle.php" class="nav-link active bg-warning text-dark fw-bold">
                    <i class="fa-solid fa-pen-nib me-2"></i> Blog Yazısı Ekle
                </a>
                <a href="forum.php" class="nav-link text-white"> 
                    <i class="fa-solid fa-comments me-2"></i> Forum Yönetimi
                </a>
            </li>
            <li class="nav-item mt-5">
                <a href="../index.php" class="nav-link text-danger fw-bold border border-danger">
                    <i class="fa-solid fa-arrow-left me-2"></i> Siteye Dön
                </a>
                <a href="uyeler.php" class="nav-link text-white"> 
                    <i class="fa-solid fa-users me-2"></i> Üyeler & Yetki
                </a>
            </li>
        </ul>
    </div>

    <div class="container-fluid p-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-dark">✍️ Yeni İçerik Oluştur</h2>
                    <a href="index.php" class="btn btn-outline-secondary d-md-none">Geri</a>
                </div>

                <?php echo $mesaj; ?>

                <div class="card shadow border-0 rounded-4">
                    <div class="card-body p-5">
                        <form method="POST">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">BLOG BAŞLIĞI</label>
                                <input type="text" name="baslik" class="form-control form-control-lg bg-light" placeholder="Örn: Şekerin Zararları Nelerdir?" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">KAPAK RESMİ (URL)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-image text-muted"></i></span>
                                    <input type="text" name="resim" class="form-control bg-light border-start-0" placeholder="https://ornek.com/resim.jpg" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">İÇERİK</label>
                                <textarea name="icerik" class="form-control bg-light" rows="10" placeholder="Yazını buraya dök..." required></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary py-3 fw-bold fs-5 shadow-sm">
                                    <i class="fa-solid fa-paper-plane me-2"></i> YAYINLA
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

</body>
</html>