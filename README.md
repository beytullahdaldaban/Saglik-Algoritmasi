# 🧬 SAĞLIK ALGORİTMASI (NutriLogic)

> **Veri Odaklı Bütüncül Yaşam ve Besin Yönetim Platformu**

![PHP](https://img.shields.io/badge/Backend-PHP%208-blue?style=for-the-badge&logo=php)
![DB](https://img.shields.io/badge/Database-SQLite-lightgrey?style=for-the-badge&logo=sqlite)
![Frontend](https://img.shields.io/badge/Frontend-Bootstrap%205-purple?style=for-the-badge&logo=bootstrap)
![API](https://img.shields.io/badge/API-OpenFoodFacts-green?style=for-the-badge&logo=json)

## 🎯 Proje Hakkında
Bu proje, kullanıcıların paketli gıdalardaki **gizli şeker** ve besin değerlerini analiz etmesini sağlayan, aynı zamanda **kişisel sağlık verilerini** (Vücut Kitle İndeksi, Yağ Oranı, Su İhtiyacı) bilimsel algoritmalarla hesaplayan web tabanlı bir yaşam asistanıdır.

---

## 🛠️ Kurulum ve Çalıştırma (Adım Adım)

Bu proje **SQLite** veritabanı kullandığı için harici bir SQL dosyası import etmenize gerek yoktur. Dosyalar olduğu gibi çalışır.

### 1. Gereksinimler
Bilgisayarınızda yerel sunucu olarak **XAMPP**, **WAMP** veya **MAMP** yüklü olmalıdır.

### 2. Kurulum
1.  Bu repoyu indirin (Sağ üstteki **Code** > **Download ZIP**).
2.  İndirdiğiniz klasörün adını `Saglik-Algoritmasi` olarak değiştirin.
3.  Klasörü `C:\xampp\htdocs\` dizininin içine taşıyın.

### 3. Çalıştırma
1.  **XAMPP Control Panel**'i açın.
2.  **Apache** servisini başlatın (Start).
3.  Tarayıcınızı açın ve şu adrese gidin:
    ```
    http://localhost/seker_proje/index.php
    ```

### 🔑 Yönetici (Admin) Giriş Bilgileri
Admin paneline erişmek ve içerik yönetimi yapmak için:
- **E-Posta:** `admin@gmail.com` 
- **Şifre:** `admin`

---

## 🚀 Öne Çıkan Özellikler

### 1. Akıllı Ürün Analizi 🔍
`OpenFoodFacts API` entegrasyonu sayesinde global gıda veritabanına erişim sağlanır. Kullanıcı bir ürün arattığında (Örn: Nutella) anlık olarak:
- Kalori, Şeker, Protein ve Yağ değerleri çekilir.
- Veritabanına **JSON** formatında işlenir.

### 2. Bilimsel Hesaplama Motoru 🧮
Sıradan bir diyet sitesinden farklı olarak arkada çalışan matematiksel modeller vardır:
- **US Navy Metodu:** Boyun, bel ve kalça ölçüleriyle gerçekçi yağ oranı hesabı.
- **Harris-Benedict Denklemi:** Bazal metabolizma hızı ve aktiviteye göre su/kalori hedefi.

### 3. Güvenlik ve Mimari 🛡️
- **SQL Injection Koruması:** Tüm veritabanı sorgularında `PDO Prepared Statements` kullanılmıştır.
- **Şifreleme:** Kullanıcı şifreleri `password_hash()` (Argon2/Bcrypt) ile korunur.
- **Micro-Servis Mantığı:** `islemler/` klasörü altındaki dosyalar API mantığıyla asenkron çalışır.

## 📂 Proje Yapısı

```bash
Saglik-Algoritmasi/
├── assets/          # CSS, JS ve Resim dosyaları
├── database/        # SQLite veritabanı dosyası (seker_takip.sqlite)
├── hesaplamalar/    # VKI, Yağ Oranı, Su modülleri
├── includes/        # Header, Footer, DB bağlantısı
├── islemler/        # Backend API servisleri (AJAX)
├── uyelik/          # Giriş, Kayıt, Çıkış işlemleri
├── admin/           # Yönetim paneli
└── index.php        # Ana sayfa
