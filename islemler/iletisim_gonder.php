<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verileri al ve temizle (Güvenlik önlemi)
    $ad = htmlspecialchars(trim($_POST['ad']));
    $email = htmlspecialchars(trim($_POST['email']));
    $konu = htmlspecialchars(trim($_POST['konu']));
    $mesaj = htmlspecialchars(trim($_POST['mesaj']));

    if (empty($ad) || empty($email) || empty($mesaj)) {
        echo json_encode(['durum' => 'hata', 'mesaj' => 'Lütfen boş alan bırakmayın.']);
        exit;
    }

    try {
        $sql = "INSERT INTO iletisim_mesajlari (ad_soyad, email, konu, mesaj) VALUES (?, ?, ?, ?)";
        $ekle = $db->prepare($sql);
        $sonuc = $ekle->execute([$ad, $email, $konu, $mesaj]);

        if ($sonuc) {
            echo json_encode(['durum' => 'basarili', 'mesaj' => 'Mesajınız ulaştı! En kısa sürede döneceğiz.']);
        } else {
            echo json_encode(['durum' => 'hata', 'mesaj' => 'Kayıt sırasında hata oluştu.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['durum' => 'hata', 'mesaj' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['durum' => 'hata', 'mesaj' => 'Geçersiz istek.']);
}
?>