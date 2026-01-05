<?php
session_start();
require_once '../includes/db.php';

// GÜVENLİK: Sadece Admin Girebilir
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// SİLME İŞLEMİ
if (isset($_GET['islem']) && $_GET['islem'] == 'sil' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Konuyu veritabanından uçur
    $sil = $db->prepare("DELETE FROM forum WHERE id = ?");
    $sil->execute([$id]);

    // İşlem bitince sayfayı yenile (URL temizlensin)
    header("Location: forum.php");
    exit;
}

// Konuları Çek (Yazarı ile Birlikte)
// LEFT JOIN kullanarak forum tablosu ile uyeler tablosunu birleştirdik.
// Böylece konuyu açan kişinin adını da görebileceğiz.
$sql = "SELECT forum.*, uyeler.ad_soyad 
        FROM forum 
        LEFT JOIN uyeler ON forum.uye_id = uyeler.id 
        ORDER BY forum.id DESC";

$konular = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Forum Yönetimi - Admin</title>
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
                <a href="uyeler.php" class="nav-link text-white">
                    <i class="fa-solid fa-users me-2"></i> Üyeler & Yetki
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="blog_ekle.php" class="nav-link text-white">
                    <i class="fa-solid fa-pen-nib me-2"></i> Blog Yazısı Ekle
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="forum.php" class="nav-link active bg-warning text-dark fw-bold">
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
        <h2 class="fw-bold mb-4">💬 Forum Konuları</h2>
        
        <div class="card shadow border-0">
            <div class="card-body p-0">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>ID</th>
                            <th>Konu Başlığı</th>
                            <th>Yazar</th>
                            <th>Tarih</th>
                            <th class="text-end">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($konular) > 0): ?>
                            <?php foreach($konular as $konu): ?>
                                <tr>
                                    <td>#<?php echo $konu['id']; ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($konu['baslik']); ?></div>
                                        <small class="text-muted"><?php echo substr(htmlspecialchars($konu['mesaj']), 0, 50); ?>...</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <i class="fa-solid fa-user me-1"></i> <?php echo $konu['ad_soyad'] ?? 'Bilinmiyor'; ?>
                                        </span>
                                    </td>
                                    <td><small class="text-muted"><?php echo $konu['tarih']; ?></small></td>
                                    <td class="text-end">
                                        <a href="?islem=sil&id=<?php echo $konu['id']; ?>" 
                                           class="btn btn-sm btn-danger shadow-sm"
                                           onclick="return confirm('Bu konuyu kalıcı olarak silmek istediğine emin misin?')">
                                            <i class="fa-solid fa-trash"></i> Sil
                                        </a>
                                        <a href="../forum_detay.php?id=<?php echo $konu['id']; ?>" target="_blank" class="btn btn-sm btn-outline-primary shadow-sm">
                                            <i class="fa-solid fa-external-link-alt"></i> Gör
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-comment-slash fa-3x mb-3"></i><br>
                                    Henüz hiç konu açılmamış.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>