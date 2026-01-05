<?php require_once 'includes/db.php'; ?>
<?php require_once 'includes/header.php'; ?>

<div class="container py-5">
    <h2 class="fw-bold mb-4 text-center">Sağlıklı Yaşam Blogu</h2>
    <div class="row g-4">
        <?php
        $sorgu = $db->query("SELECT * FROM blog ORDER BY tarih DESC");
        while($yazi = $sorgu->fetch(PDO::FETCH_ASSOC)) {
            // Türkçe karakterleri bozmadan 100 harf alıp sonuna ... ekler
            $ozet = mb_substr(strip_tags($yazi['icerik']), 0, 100, 'UTF-8') . '...';
            
            echo '
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="'.$yazi['resim'].'" class="card-img-top" style="height: 200px; object-fit: cover;" onerror="this.src=\'https://placehold.co/600x400?text=Resim+Yok\'">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">'.$yazi['baslik'].'</h5>
                        <p class="card-text text-muted">'.$ozet.'</p>
                        
                        <a href="blog_detay.php?id='.$yazi['id'].'" class="btn btn-outline-primary btn-sm rounded-pill px-4">Devamını Oku</a>
                    </div>
                    <div class="card-footer bg-white text-muted small border-0">
                        <i class="fa-regular fa-calendar me-1"></i> '.date("d.m.Y", strtotime($yazi['tarih'])).'
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>