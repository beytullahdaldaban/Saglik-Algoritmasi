<?php
// DİKKAT: Admin klasöründe olduğumuz için '../' ile bir geri çıkıp db'yi buluyoruz
require_once '../includes/db.php';

echo "<h3>⚙️ Yönetici Yetkilendirme Aracı</h3><hr>";

try {
    // 1. ADIM: 'rol' sütunu yoksa oluştur
    // SQLite'da "IF NOT EXISTS" sütun için her sürümde çalışmayabilir, o yüzden try-catch ile yapıyoruz.
    try {
        $db->exec("ALTER TABLE uyeler ADD COLUMN rol TEXT DEFAULT 'uye'");
        echo "<p style='color:green'>✅ Tablo güncellendi: 'rol' sütunu eklendi.</p>";
    } catch (PDOException $e) {
        // Sütun zaten varsa hata verir, sorun değil
        echo "<p style='color:blue'>ℹ️ Bilgi: 'rol' sütunu zaten mevcut.</p>";
    }

    // 2. ADIM: ID'si 1 olanı Admin yap
    // Senin ID'n 1 değilse burayı değiştir!
    $kullanici_id = 1; 
    
    $stmt = $db->prepare("UPDATE uyeler SET rol = 'admin' WHERE id = ?");
    $stmt->execute([$kullanici_id]);
    
    echo "<p style='color:green'>🎉 Tebrikler! ID'si <b>$kullanici_id</b> olan kullanıcı artık <b>ADMİN</b>.</p>";

} catch (PDOException $e) {
    echo "<p style='color:red'>❌ Bir hata oluştu: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<a href='../uyelik/giris.php'>Çıkış Yapıp Tekrar Giriş Yap (Tıkla)</a>";
?>