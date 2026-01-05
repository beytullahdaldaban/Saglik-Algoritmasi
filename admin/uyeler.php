<?php
session_start();
require_once '../includes/db.php';

// GÜVENLİK: Sadece Admin Girebilir
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// RÜTBE GÜNCELLEME İŞLEMİ
if (isset($_GET['islem']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $islem = $_GET['islem'];

    // Kendini yetkisizleştirmeyi engelle (Yoksa panelden atılırsın!)
    if ($id == $_SESSION['uye_id']) {
        echo "<script>alert('Kendini silemezsin veya yetkini alamazsın patron!'); window.location.href='uyeler.php';</script>";
        exit;
    }

    if ($islem == 'admin_yap') {
        $db->prepare("UPDATE uyeler SET rol = 'admin' WHERE id = ?")->execute([$id]);
    } elseif ($islem == 'uye_yap') {
        $db->prepare("UPDATE uyeler SET rol = 'uye' WHERE id = ?")->execute([$id]);
    } elseif ($islem == 'sil') {
        $db->prepare("DELETE FROM uyeler WHERE id = ?")->execute([$id]);
    }
    
    header("Location: uyeler.php"); // Sayfayı yenile
    exit;
}

// Üyeleri Çek
$uyeler = $db->query("SELECT * FROM uyeler ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Üye Yönetimi - Admin</title>
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
                <a href="uyeler.php" class="nav-link active bg-warning text-dark fw-bold">
                    <i class="fa-solid fa-users me-2"></i> Üyeler & Yetki
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="blog_ekle.php" class="nav-link text-white">
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
            </li>
        </ul>
    </div>

    <div class="container-fluid p-5">
        <h2 class="fw-bold mb-4">👥 Üye Listesi ve Yetkilendirme</h2>
        
        <div class="card shadow border-0">
            <div class="card-body p-0">
                <table class="table table-hover table-striped mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>ID</th>
                            <th>Ad Soyad</th>
                            <th>E-Posta</th>
                            <th>Rütbe</th>
                            <th class="text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($uyeler as $uye): ?>
                            <tr>
                                <td>#<?php echo $uye['id']; ?></td>
                                <td class="fw-bold"><?php echo $uye['ad_soyad']; ?></td>
                                <td><?php echo $uye['email']; ?></td>
                                <td>
                                    <?php if($uye['rol'] == 'admin'): ?>
                                        <span class="badge bg-danger">YÖNETİCİ</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Üye</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <?php if($uye['rol'] == 'uye'): ?>
                                        <a href="?islem=admin_yap&id=<?php echo $uye['id']; ?>" class="btn btn-sm btn-outline-success fw-bold" onclick="return confirm('Bu kullanıcıyı Admin yapmak istediğine emin misin?')">
                                            <i class="fa-solid fa-crown"></i> Admin Yap
                                        </a>
                                    <?php else: ?>
                                        <?php if($uye['id'] != $_SESSION['uye_id']): ?>
                                            <a href="?islem=uye_yap&id=<?php echo $uye['id']; ?>" class="btn btn-sm btn-outline-warning fw-bold" onclick="return confirm('Yetkisini almak istediğine emin misin?')">
                                                <i class="fa-solid fa-user-minus"></i> Üye Yap
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small me-2">(Sen)</span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if($uye['id'] != $_SESSION['uye_id']): ?>
                                        <a href="?islem=sil&id=<?php echo $uye['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu kullanıcıyı silmek istediğine emin misin?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>