<?php 
require_once '../includes/db.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad']);
    $email = trim($_POST['email']);
    $sifre = $_POST['sifre'];

    // Şifreyi güvenli hale getir (Rubrik: Şifreleme Mantığı)
    $hashliSifre = password_hash($sifre, PASSWORD_DEFAULT);

    try {
        $ekle = $db->prepare("INSERT INTO uyeler (ad_soyad, email, sifre) VALUES (?, ?, ?)");
        $ekle->execute([$ad, $email, $hashliSifre]);
        
        echo "<script>
            Swal.fire('Başarılı!', 'Aramıza hoş geldin! Şimdi giriş yapabilirsin.', 'success')
            .then(() => window.location.href = 'giris.php');
        </script>";
    } catch (PDOException $e) {
        echo "<script>Swal.fire('Hata', 'Bu e-posta zaten kullanılıyor!', 'error');</script>";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h3 class="text-center fw-bold mb-4">Kayıt Ol 📝</h3>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Ad Soyad</label>
                            <input type="text" name="ad" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>E-Posta</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Şifre</label>
                            <input type="password" name="sifre" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">KAYIT OL</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="giris.php">Zaten üye misin? Giriş Yap</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>