<?php
require_once '../includes/db.php';
session_start();

// Sadece POST isteği gelirse ve kullanıcı giriş yapmışsa çalışır
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_SESSION['uye_id'])) {
    
    $silinecekID = $_POST['id'];
    $uyeID = $_SESSION['uye_id'];

    // SQL: Sadece bu ID'ye sahip VE bu üye tarafından eklenmiş kaydı sil
    $sorgu = $db->prepare("DELETE FROM gidalar WHERE id = ? AND uye_id = ?");
    $sonuc = $sorgu->execute([$silinecekID, $uyeID]);

    if ($sonuc) {
        echo json_encode(['durum' => 'basarili']);
    } else {
        echo json_encode(['durum' => 'hata']);
    }
}
?>