<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$yol = file_exists('assets') ? '' : '../';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sağlık Algoritması 🕵️‍♂️</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $yol; ?>assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase" href="<?php echo $yol; ?>index.php">
            <i class="fa-solid fa-magnifying-glass-chart"></i> Sağlık Algoritması
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="<?php echo $yol; ?>index.php">Ana Sayfa</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $yol; ?>analizlerim.php">Analizlerim</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $yol; ?>Hesaplamalar/index.php">Hesaplamalar</a></li>
                <li class="nav-item"><a class="nav-link fw-bold text-warning" href="<?php echo $yol; ?>takip.php"><i class="fa-solid fa-chart-line"></i> Günlük Takip</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $yol; ?>blog.php">Blog</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $yol; ?>forum.php">Forum</a></li>
                <li class="nav-item"><a class="nav-link" href="iletisim.php"><i class="fa-solid fa-envelope me-1"></i> İletişim</a></li>
                
                <?php if(isset($_SESSION['uye_id'])): ?>
                    <li class="nav-item dropdown ms-3">
                        <a class="nav-link dropdown-toggle btn btn-light text-primary px-3 rounded-pill" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-user"></i> <?php echo $_SESSION['uye_adi']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item text-danger" href="<?php echo $yol; ?>uyelik/cikis.php"><i class="fa-solid fa-sign-out-alt"></i> Çıkış Yap</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-3"><a class="btn btn-outline-light rounded-pill px-4 btn-sm" href="<?php echo $yol; ?>uyelik/giris.php">Giriş Yap</a></li>
                    <li class="nav-item ms-2"><a class="btn btn-warning rounded-pill px-4 btn-sm fw-bold text-dark shadow-sm" href="<?php echo $yol; ?>uyelik/kayit.php"><i class="fa-solid fa-user-plus"></i> Kayıt Ol</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script>
    const UYE_GIRIS_YAPTI = <?php echo isset($_SESSION['uye_id']) ? 'true' : 'false'; ?>;
</script>