<?php
require_once '../includes/db.php';
session_start();

header('Content-Type: application/json');

// Giriş kontrolü
if (!isset($_SESSION['uye_id'])) {
    echo json_encode(['durum' => 'hata', 'mesaj' => 'Giriş yapmalısın.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // JSON verisini al
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['hedef'])) {
        $hedef = intval($data['hedef']);
        $uye_id = $_SESSION['uye_id'];

        // Veritabanını güncelle (Zaten var olan sütun)
        $guncelle = $db->prepare("UPDATE uyeler SET gunluk_kalori_hedefi = ? WHERE id = ?");
        $sonuc = $guncelle->execute([$hedef, $uye_id]);

        if ($sonuc) {
            echo json_encode(['durum' => 'basarili', 'mesaj' => 'Günlük hedefin güncellendi!']);
        } else {
            echo json_encode(['durum' => 'hata', 'mesaj' => 'Veritabanı hatası.']);
        }
    } else {
        echo json_encode(['durum' => 'hata', 'mesaj' => 'Hedef değeri gelmedi.']);
    }
} else {
    echo json_encode(['durum' => 'hata', 'mesaj' => 'Geçersiz istek.']);
}
?>