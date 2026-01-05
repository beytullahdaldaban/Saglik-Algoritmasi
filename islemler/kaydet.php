<?php
session_start();
require_once '../includes/db.php'; // Dosya yolunun doğru olduğundan eminiz

$json = file_get_contents('php://input');
$veri = json_decode($json, true);

header('Content-Type: application/json');

if (!isset($_SESSION['uye_id'])) {
    echo json_encode(['durum' => 'hata', 'mesaj' => 'Giriş yapmalısın.']);
    exit;
}

if ($veri) {
    try {
        $uye_id = $_SESSION['uye_id'];
        
        // Verileri al ve güvenli hale getir
        $urun_adi = htmlspecialchars(trim($veri['urun_adi']));
        $marka = htmlspecialchars(trim($veri['marka']));
        $tur = htmlspecialchars(trim($veri['tur']));
        $resim = $veri['resim']; // URL olduğu için dokunmuyoruz

        // Sayısal değerler
        $kalori = floatval($veri['kalori']);
        $seker = floatval($veri['seker']);
        $protein = floatval($veri['protein']);
        $yag = floatval($veri['yag']);
        $karb = floatval($veri['karb']);
        $tuz = floatval($veri['tuz']); // Tuz verisini de alıyoruz

        $sql = "INSERT INTO gidalar 
                (uye_id, urun_adi, marka, kalori_100gr, seker_100gr, protein, yag, karb, tuz, resim_url, tur, tarih) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now', 'localtime'))";

        $ekle = $db->prepare($sql);
        $sonuc = $ekle->execute([$uye_id, $urun_adi, $marka, $kalori, $seker, $protein, $yag, $karb, $tuz, $resim, $tur]);

        if ($sonuc) {
            echo json_encode(['durum' => 'basarili', 'mesaj' => 'Kaydedildi']);
        } else {
            echo json_encode(['durum' => 'hata', 'mesaj' => 'Veritabanı kaydı başarısız.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['durum' => 'hata', 'mesaj' => 'DB Hatası: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['durum' => 'hata', 'mesaj' => 'Veri alınamadı.']);
}
?>