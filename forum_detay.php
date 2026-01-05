<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 

// --- OTOMATİK TABLO KURULUMU (Senin uğraşmaman için) ---
try {
    $db->exec("CREATE TABLE IF NOT EXISTS forum_cevaplar (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        forum_id INTEGER,
        uye_id INTEGER,
        mesaj TEXT,
        tarih DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) { /* Hata olursa sessizce geç */ }

// ID Kontrolü
if (!isset($_GET['id'])) { header("Location: admin/forum.php"); exit; }
$forum_id = $_GET['id'];

// --- CEVAP GÖNDERME İŞLEMİ ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cevap'])) {
    // Giriş yapmamışsa engelle
    if (!isset($_SESSION['uye_id'])) {
        echo "<script>Swal.fire('Hata', 'Cevap yazmak için giriş yapmalısın!', 'error');</script>";
    } else {
        $cevap = htmlspecialchars(trim($_POST['cevap']));
        if (!empty($cevap)) {
            $ekle = $db->prepare("INSERT INTO forum_cevaplar (forum_id, uye_id, mesaj) VALUES (?, ?, ?)");
            $ekle->execute([$forum_id, $_SESSION['uye_id'], $cevap]);
            
            // Sayfayı yenile ki mesaj görünsün
            echo "<script>window.location.href = 'forum_detay.php?id=$forum_id';</script>"; 
        }
    }
}

// --- VERİLERİ ÇEKME ---
// 1. Ana Konuyu Çek
$sorgu = $db->prepare("SELECT forum.*, uyeler.ad_soyad FROM forum 
                       LEFT JOIN uyeler ON forum.uye_id = uyeler.id 
                       WHERE forum.id = ?");
$sorgu->execute([$forum_id]);
$konu = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$konu) die("<div class='container py-5 text-center'><h3>Konu bulunamadı! 🤷‍♂️</h3></div>");

// 2. Cevapları Çek (Yazan kişinin adıyla birlikte)
$cevaplar = $db->prepare("SELECT forum_cevaplar.*, uyeler.ad_soyad FROM forum_cevaplar 
                          LEFT JOIN uyeler ON forum_cevaplar.uye_id = uyeler.id 
                          WHERE forum_id = ? ORDER BY id ASC");
$cevaplar->execute([$forum_id]);
$liste = $cevaplar->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <a href="admin/forum.php" class="btn btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left"></i> Listeye Dön</a>

            <div class="card shadow border-0 rounded-4 mb-5">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-warning text-dark me-2">Konu</span>
                        <small class="opacity-75"><i class="fa-regular fa-clock me-1"></i> <?php echo $konu['tarih']; ?></small>
                    </div>
                    <h2 class="fw-bold mb-0"><?php echo htmlspecialchars($konu['konu_basligi']); ?></h2>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 50px; height: 50px; font-size: 1.5rem;">
                            <?php echo strtoupper(substr($konu['ad_soyad'] ?? 'M', 0, 1)); ?>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-bold mb-0 text-primary"><?php echo $konu['ad_soyad'] ?? 'Misafir'; ?></h6>
                            <small class="text-muted">Konu Sahibi</small>
                        </div>
                    </div>
                    <div class="fs-5 text-dark" style="line-height: 1.8;">
                        <?php echo nl2br(htmlspecialchars($konu['mesaj'])); ?>
                    </div>
                </div>
            </div>

            <h4 class="mb-4 fw-bold text-secondary"><i class="fa-solid fa-comments me-2"></i> Cevaplar (<?php echo count($liste); ?>)</h4>
            
            <?php if(count($liste) > 0): ?>
                <?php foreach($liste as $cvp): ?>
                    <div class="card shadow-sm border-0 mb-3 ms-4" style="border-left: 5px solid #0d6efd !important;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <strong class="text-primary"><?php echo $cvp['ad_soyad']; ?></strong>
                                <small class="text-muted"><?php echo $cvp['tarih']; ?></small>
                            </div>
                            <p class="mb-0 text-dark"><?php echo nl2br(htmlspecialchars($cvp['mesaj'])); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-light border text-center text-muted mb-4">
                    Henüz kimse cevap yazmamış. İlk yazan sen ol! 👇
                </div>
            <?php endif; ?>

            <div class="card shadow-lg border-0 rounded-4 mt-5">
                <div class="card-body p-4 bg-light">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-reply"></i> Bir Cevap Yaz</h5>
                    
                    <?php if(isset($_SESSION['uye_id'])): ?>
                        <form method="POST"> 
                            <textarea name="cevap" class="form-control bg-white shadow-none mb-3 p-3" rows="3" placeholder="Fikrini buraya yaz..." required style="border: 1px solid #ddd;"></textarea>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill">
                                    Gönder <i class="fa-solid fa-paper-plane ms-2"></i>
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning border-0 shadow-sm">
                            <i class="fa-solid fa-lock me-2"></i> Cevap yazmak için <a href="uyelik/giris.php" class="fw-bold text-dark text-decoration-underline">giriş yapmalısın.</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>