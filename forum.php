<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 

// --- 1. YENİ KONU EKLEME İŞLEMİ ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['baslik'])) {
    $baslik = htmlspecialchars(trim($_POST['baslik']));
    $icerik = htmlspecialchars(trim($_POST['icerik']));
    
    // Giriş yapmış mı kontrolü
    if(isset($_SESSION['uye_id'])) {
        $uye_id = $_SESSION['uye_id'];
        $uye_adi = $_SESSION['uye_adi'];

        if(!empty($baslik) && !empty($icerik)) {
            $ekle = $db->prepare("INSERT INTO forum (konu_basligi, mesaj, uye_id, uye_adi, tarih) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)");
            $islem = $ekle->execute([$baslik, $icerik, $uye_id, $uye_adi]);

            if($islem) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Harika!',
                        text: 'Konun başarıyla yayınlandı.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => { window.location.href = 'forum.php'; });
                </script>";
            } else {
                echo "<script>Swal.fire('Hata', 'Konu eklenirken bir sorun oluştu.', 'error');</script>";
            }
        }
    } else {
        echo "<script>Swal.fire('Hata', 'Konu açmak için giriş yapmalısın.', 'warning');</script>";
    }
}

// --- 2. KONULARI LİSTELEME İŞLEMİ (Yorum Sayısıyla Birlikte) ---
try {
    // Alt sorgu (Subquery) ile her konuya ait cevap sayısını (yorum_sayisi) da çekiyoruz
    $sql = "SELECT forum.*, 
            (SELECT COUNT(*) FROM forum_cevaplar WHERE forum_cevaplar.forum_id = forum.id) as yorum_sayisi 
            FROM forum ORDER BY id DESC";
    
    $sorgu = $db->query($sql);
    $konular = $sorgu->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $konular = [];
}
?>

<div class="container py-5">
    
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold"><i class="fa-solid fa-comments text-primary"></i> Forum Alanı</h2>
            <p class="text-muted">Sorularını sor, tecrübelerini paylaş.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <?php if(isset($_SESSION['uye_id'])): ?>
                <button class="btn btn-primary rounded-pill px-4 shadow" onclick="yeniKonuModal()">
                    <i class="fa-solid fa-plus"></i> Yeni Konu Aç
                </button>
            <?php else: ?>
                <a href="uyelik/giris.php" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fa-solid fa-lock"></i> Konu Açmak İçin Giriş Yap
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="list-group list-group-flush">
            <?php if(empty($konular)): ?>
                <div class="text-center p-5 text-muted">
                    <i class="fa-regular fa-comment-dots fa-3x mb-3 opacity-25"></i>
                    <h4>Henüz konu açılmamış.</h4>
                    <p>İlk soruyu sen sormak ister misin?</p>
                </div>
            <?php else: ?>
                <?php foreach($konular as $konu): ?>
                    <a href="forum_detay.php?id=<?php echo $konu['id']; ?>" class="list-group-item list-group-item-action p-4">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1 fw-bold text-dark">
                                <i class="fa-regular fa-comment-alt text-primary me-2"></i> 
                                <?php echo htmlspecialchars($konu['konu_basligi']); ?>
                            </h5>
                            <small class="text-muted">
                                <i class="fa-regular fa-clock"></i> <?php echo date("d.m.Y", strtotime($konu['tarih'])); ?>
                            </small>
                        </div>
                        <p class="mb-2 text-muted mt-2 text-truncate" style="max-width: 800px;">
                            <?php echo htmlspecialchars($konu['mesaj']); ?>
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-primary fw-bold bg-primary bg-opacity-10 px-2 py-1 rounded">
                                <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($konu['uye_adi']); ?>
                            </small>
                            <small class="text-muted">
                                <i class="fa-solid fa-reply-all me-1"></i> <?php echo $konu['yorum_sayisi']; ?> Yorum
                            </small>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<form id="konuFormu" method="POST" style="display:none;">
    <input type="text" name="baslik" id="formBaslik">
    <textarea name="icerik" id="formIcerik"></textarea>
</form>

<script>
function yeniKonuModal() {
    Swal.fire({
        title: 'Yeni Konu Başlat',
        html: `
            <input type="text" id="swal-input1" class="swal2-input" placeholder="Konu Başlığı">
            <textarea id="swal-input2" class="swal2-textarea" placeholder="Mesajınız..." style="height:150px;"></textarea>
        `,
        confirmButtonText: 'Yayınla',
        showCancelButton: true,
        cancelButtonText: 'Vazgeç',
        focusConfirm: false,
        preConfirm: () => {
            const baslik = document.getElementById('swal-input1').value;
            const icerik = document.getElementById('swal-input2').value;

            if (!baslik || !icerik) {
                Swal.showValidationMessage('Lütfen tüm alanları doldurun');
                return false;
            }

            document.getElementById('formBaslik').value = baslik;
            document.getElementById('formIcerik').value = icerik;
            document.getElementById('konuFormu').submit();
        }
    });
}
</script>

<?php require_once 'includes/footer.php'; ?>