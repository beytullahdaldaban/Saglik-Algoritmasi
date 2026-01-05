<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 

// GÜVENLİK: Giriş yapmamışsa bu sayfayı göremez
if(!isset($_SESSION['uye_id'])) {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Giriş Yapmalısın',
            text: 'Günlük takibini görmek için lütfen önce giriş yap.',
            confirmButtonText: 'Giriş Yap',
            confirmButtonColor: '#ffc107'
        }).then((result) => {
            window.location.href = 'uyelik/giris.php';
        });
    </script>";
    exit;
}

$uye_id = $_SESSION['uye_id'];

// --- 1. KİŞİSEL HEDEFİ VERİTABANINDAN ÇEK (DÜZELTİLEN KISIM) ---
$hedefSorgu = $db->prepare("SELECT gunluk_kalori_hedefi FROM uyeler WHERE id = ?");
$hedefSorgu->execute([$uye_id]);
$hedefSonuc = $hedefSorgu->fetch(PDO::FETCH_ASSOC);

// Eğer veritabanında hedef varsa ve 0'dan büyükse onu al, yoksa varsayılan 2000 yap
$hedef = ($hedefSonuc && $hedefSonuc['gunluk_kalori_hedefi'] > 0) ? intval($hedefSonuc['gunluk_kalori_hedefi']) : 2000;
// ---------------------------------------------------------------

// 2. BUGÜN YENENLERİ ÇEK
$sorgu = $db->prepare("SELECT * FROM gidalar WHERE uye_id = ? AND tur = 'tuketim' AND date(tarih) = date('now', 'localtime') ORDER BY id DESC");
$sorgu->execute([$uye_id]);
$bugunYenenler = $sorgu->fetchAll(PDO::FETCH_ASSOC);

// Toplam Hesapla
$toplamKalori = 0;
$toplamSeker = 0;
foreach($bugunYenenler as $yemek) {
    $toplamKalori += floatval($yemek['kalori_100gr']); 
    $toplamSeker += floatval($yemek['seker_100gr']);   
}

// İlerleme Çubuğu Yüzdesi
$yuzde = ($hedef > 0) ? ($toplamKalori / $hedef) * 100 : 0;
// Görsel taşmasın diye %100'e sabitliyoruz (ama hesaplamada kullanmıyoruz)
$barYuzde = ($yuzde > 100) ? 100 : $yuzde;

// Renk Ayarı
$barRenk = "bg-gradient-warning"; 
if($toplamKalori > $hedef) {
    $barRenk = "bg-danger"; 
}
?>

<div class="container py-5">
    
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="fw-bold display-6"><i class="fa-solid fa-chart-line text-warning"></i> Günlük Takip</h2>
            <p class="text-muted">Bugün yediklerin ve hedefine olan uzaklığın.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="d-inline-block bg-white p-3 rounded shadow-sm border">
                <label class="fw-bold small text-muted d-block text-start">GÜNLÜK HEDEFİN</label>
                <div class="input-group mt-1">
                    <input type="number" id="hedefInput" class="form-control fw-bold fs-4 text-warning border-warning bg-light" value="<?php echo $hedef; ?>" style="width: 120px;" disabled>
                    <span class="input-group-text bg-warning text-dark fw-bold">kcal</span>
                </div>
                <small class="text-muted d-block mt-1 text-start" style="font-size: 0.7rem;">
                    Değiştirmek için <a href="Hesaplamalar/kalori.php" class="text-decoration-none fw-bold">Kalori Hesapla</a>
                </small>
            </div>
        </div>
    </div>

    <div class="card shadow-lg border-0 mb-5 overflow-hidden">
        <div class="card-body p-5 text-center bg-dark text-white">
            <h5 class="text-white-50 mb-4 ls-2">KALORİ DURUMUN</h5>
            
            <div class="progress mb-4" style="height: 40px; border-radius: 20px; background: rgba(255,255,255,0.1);">
                <div id="kaloriBar" class="progress-bar <?php echo $barRenk; ?> progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo $barYuzde; ?>%">
                    <span class="fw-bold fs-5 text-dark" id="barYazi">%<?php echo round($yuzde); ?></span>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-4 border-end border-secondary">
                    <h3 class="fw-bold mb-0 text-warning"><?php echo $toplamKalori; ?></h3>
                    <small class="text-white-50">ALINAN</small>
                </div>
                <div class="col-4 border-end border-secondary">
                    <h3 class="fw-bold mb-0 text-white" id="hedefYazi"><?php echo $hedef; ?></h3>
                    <small class="text-white-50">HEDEF</small>
                </div>
                <div class="col-4">
                    <?php 
                        $kalan = $hedef - $toplamKalori;
                        $kalanYazi = ($kalan < 0) ? abs($kalan) . " FAZLA" : $kalan;
                        $kalanRenk = ($kalan < 0) ? "text-danger" : "text-success";
                    ?>
                    <h3 class="fw-bold mb-0 <?php echo $kalanRenk; ?>" id="kalanYazi"><?php echo $kalanYazi; ?></h3>
                    <small class="text-white-50">KALAN</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm bg-danger text-white">
                <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                    <i class="fa-solid fa-cubes-stacked fa-3x mb-3 opacity-50"></i>
                    <h2 class="display-4 fw-bold"><?php echo $toplamSeker; ?> gr</h2>
                    <h5>Şeker Alımı</h5>
                    <p class="small opacity-75 mb-0">Dikkat: Fazla şeker sağlığa zararlıdır.</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fa-solid fa-utensils text-muted me-2"></i> Bugün Eklediklerin</h5>
                    
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-arrow-down-short-wide"></i> Sırala
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="listeyiSirala('kalori')">🔥 Kaloriye Göre (Yüksek)</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="listeyiSirala('seker')">🍬 Şekere Göre (Yüksek)</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="location.reload()">🔄 Varsayılan</a></li>
                        </ul>
                    </div>
                </div>

                <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                    <?php if(empty($bugunYenenler)): ?>
                        <div class="text-center p-5 text-muted">
                            <i class="fa-solid fa-plate-wheat fa-3x mb-3 opacity-25"></i>
                            <p>Listen bomboş! Ana sayfadan bir şeyler ekle.</p>
                            <a href="index.php" class="btn btn-warning btn-sm rounded-pill mt-2 fw-bold">Gıda Ekle</a>
                        </div>
                    <?php else: ?>
                        <?php foreach($bugunYenenler as $gida): ?>
                            <div class="list-group-item p-3 d-flex align-items-center border-bottom-0 mb-2 rounded bg-light mx-3">
                                <img src="<?php echo $gida['resim_url']; ?>" 
                                     class="rounded me-3 bg-white shadow-sm" 
                                     width="50" height="50" 
                                     style="object-fit: contain;"
                                     onerror="this.src='https://placehold.co/50'">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold"><?php echo $gida['urun_adi']; ?></h6>
                                    <small class="text-muted"><?php echo $gida['marka']; ?></small>
                                </div>
                                <div class="text-end me-3">
                                    <span class="badge bg-white text-dark border shadow-sm d-block mb-1"><?php echo $gida['kalori_100gr']; ?> kcal</span>
                                    <small class="text-danger fw-bold" style="font-size: 0.7rem;"><?php echo $gida['seker_100gr']; ?>g Şeker</small>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-danger border-0" onclick="sil(<?php echo $gida['id']; ?>)">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// --- JS SIRALAMA FONKSİYONU ---
function listeyiSirala(kriter) {
    let listeContainer = document.querySelector(".list-group-flush");
    let ogeler = Array.from(listeContainer.getElementsByClassName("list-group-item"));

    if(ogeler.length === 0) return;

    ogeler.sort((a, b) => {
        let valA, valB;
        if (kriter === 'kalori') {
            valA = parseFloat(a.querySelector(".badge").innerText);
            valB = parseFloat(b.querySelector(".badge").innerText);
        } else {
            valA = parseFloat(a.querySelector(".text-danger").innerText);
            valB = parseFloat(b.querySelector(".text-danger").innerText);
        }
        return valB - valA; // Büyükten küçüğe
    });

    listeContainer.innerHTML = "";
    ogeler.forEach(oge => listeContainer.appendChild(oge));
    
    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Liste Sıralandı!', showConfirmButton: false, timer: 1000 });
}

// --- SİLME İŞLEMİ ---
function sil(id) {
    Swal.fire({
        title: 'Emin misin?',
        text: "Bu kaydı silmek istiyor musun?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Evet, Sil'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('islemler/sil.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + id
            })
            .then(response => response.json())
            .then(data => {
                if(data.durum === 'basarili') {
                    location.reload();
                } else {
                    Swal.fire('Hata', 'Silinemedi.', 'error');
                }
            });
        }
    });
}
</script>

<style>
    .bg-gradient-warning { background: linear-gradient(45deg, #ffc107, #ffca2c); }
    .ls-2 { letter-spacing: 2px; }
</style>

<?php require_once 'includes/footer.php'; ?>