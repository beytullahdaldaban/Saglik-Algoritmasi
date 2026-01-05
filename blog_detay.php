<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 

// URL'den ID'yi alıyoruz (blog_detay.php?id=5 gibi)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Veritabanından o ID'ye sahip yazıyı çekiyoruz
$sorgu = $db->prepare("SELECT * FROM blog WHERE id = ?");
$sorgu->execute([$id]);
$yazi = $sorgu->fetch(PDO::FETCH_ASSOC);

// Eğer yazı bulunamazsa hata mesajı göster
if(!$yazi) {
    echo '<div class="container py-5 text-center">
            <div class="alert alert-warning">Aradığınız yazı bulunamadı!</div>
            <a href="blog.php" class="btn btn-primary">Geri Dön</a>
          </div>';
    require_once 'includes/footer.php';
    exit;
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <a href="blog.php" class="text-decoration-none text-muted mb-4 d-inline-block">
                <i class="fa-solid fa-arrow-left me-1"></i> Blog Listesine Dön
            </a>

            <h1 class="fw-bold text-dark mb-3 display-5"><?php echo $yazi['baslik']; ?></h1>
            
            <div class="text-muted mb-4">
                <i class="fa-regular fa-calendar me-2"></i> <?php echo date("d F Y, H:i", strtotime($yazi['tarih'])); ?>
            </div>

            <div class="rounded-4 overflow-hidden shadow-sm mb-5">
                <img src="<?php echo $yazi['resim']; ?>" class="w-100" style="max-height: 450px; object-fit: cover;" onerror="this.src='https://placehold.co/800x400?text=Resim+Yok'">
            </div>

            <div class="blog-icerik fs-5 lh-lg text-secondary">
                <?php echo nl2br($yazi['icerik']); ?>
            </div>

        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>