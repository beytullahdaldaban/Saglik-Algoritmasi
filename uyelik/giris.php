<?php 
require_once '../includes/db.php';
require_once '../includes/header.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $sifre = $_POST['sifre'];

    $sorgu = $db->prepare("SELECT * FROM uyeler WHERE email = ?");
    $sorgu->execute([$email]);
    $uye = $sorgu->fetch(PDO::FETCH_ASSOC); // Veriyi $uye içine attık

    // Şifre kontrolü
    if ($uye && password_verify($sifre, $uye['sifre'])) {
        
        // Oturumu Başlat
        $_SESSION['uye_id'] = $uye['id'];
        $_SESSION['uye_adi'] = $uye['ad_soyad'];
        
        // --- DÜZELTİLEN SATIR BURASI ---
        $_SESSION['rol'] = $uye['rol']; 
        // -------------------------------
        
        echo "<script>window.location.href = '../index.php';</script>";
    } else {
        echo "<script>Swal.fire('Hata', 'E-posta veya şifre yanlış!', 'error');</script>";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h3 class="text-center fw-bold mb-4">Giriş Yap 🔑</h3>
                    <form method="POST">
                        <div class="mb-3">
                            <label>E-Posta</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Şifre</label>
                            <input type="password" name="sifre" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">GİRİŞ YAP</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="kayit.php">Hesabın yok mu? Kayıt Ol</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>