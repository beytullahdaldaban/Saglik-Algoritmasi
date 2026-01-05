<?php
try {
    // Veritabanı dosyamızın yolunu buluyoruz
    // __DIR__ demek: bu dosyanın olduğu yer (includes)
    // /../database/ demek: bir üst klasöre çık, database klasörüne gir
    $db_dosyasi = __DIR__ . "/../database/seker_takip.sqlite";
    
    // Veritabanı bağlantısını kuruyoruz (Rubrik: Backend Temelleri)
    $db = new PDO("sqlite:" . $db_dosyasi);
    
    // Hata modunu açalım (Rubrik: Hata Yönetimi)
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Bağlantı başarılıysa sessizce devam eder.
} catch (PDOException $e) {
    // Hata varsa ekrana basar (Güvenlik gereği normalde log dosyasına yazılır ama ödevde ekrana basabilirsin)
    die("Veritabanı bağlantı hatası kanka: " . $e->getMessage());
}
?>