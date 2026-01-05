<?php
session_start();
require_once '../includes/db.php';

// GÜVENLİK KONTROLÜ: Sadece 'admin' girebilir!
if (!isset($_SESSION['uye_id']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("<h3>Hop! Buraya giriş yetkin yok kanka. 🚫</h3><br><a href='../index.php'>Ana Sayfaya Dön</a>");
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli - Sağlık Algoritması</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="d-flex">
    <div class="bg-dark text-white p-3" style="width: 280px; min-height: 100vh;">
        <h4 class="mb-4 text-center text-warning fw-bold"><i class="fa-solid fa-user-secret"></i> ADMİN PANELİ</h4>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-2">
                <a href="index.php" class="nav-link active bg-warning text-dark fw-bold">
                    <i class="fa-solid fa-gauge me-2"></i> Kontrol Paneli
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="blog_ekle.php" class="nav-link text-white">
                    <i class="fa-solid fa-pen-nib me-2"></i> Blog Yazısı Ekle
                </a>
            </li>
            <li class="nav-item mb-2">
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
        <h2 class="fw-bold mb-4">Hoş Geldin, Patron! 👋</h2>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white shadow border-0 p-3">
                    <h3><i class="fa-solid fa-users"></i> Üyeler</h3>
                    <h1 class="fw-bold">
                        <?php echo $db->query("SELECT count(*) FROM uyeler")->fetchColumn(); ?>
                    </h1>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white shadow border-0 p-3">
                    <h3><i class="fa-solid fa-newspaper"></i> Blog Yazıları</h3>
                    <h1 class="fw-bold">
                        <?php 
                        // Blog tablosu varsa sayısını çeker, yoksa 0 yazar (Hata vermesin diye try-catch)
                        try { echo $db->query("SELECT count(*) FROM blog")->fetchColumn(); } catch(Exception $e) { echo 0; }
                        ?>
                    </h1>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white shadow border-0 p-3">
                    <h3><i class="fa-solid fa-comments"></i> Forum Konuları</h3>
                    <h1 class="fw-bold">
                        <?php echo $db->query("SELECT count(*) FROM forum")->fetchColumn(); ?>
                    </h1>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>