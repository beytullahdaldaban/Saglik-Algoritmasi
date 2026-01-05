<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="text-center mb-5">
                <h2 class="fw-bold display-6">Bizimle İletişime Geçin</h2>
                <p class="text-muted">Bir sorunuz mu var? Aşağıdaki formu doldurun, dedektiflerimiz incelesin! 🕵️‍♂️</p>
            </div>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5 bg-dark text-white p-5 d-flex flex-column justify-content-center position-relative overflow-hidden">
                        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" 
                             style="background: url('https://img.freepik.com/free-vector/healthy-food-background_23-2148488737.jpg') center/cover;"></div>
                        
                        <div class="position-relative z-1">
                            <h4 class="fw-bold mb-4">İletişim Bilgileri</h4>
                            
                            <div class="d-flex mb-4">
                                <div class="me-3 text-warning"><i class="fa-solid fa-location-dot fa-xl"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Adres</h6>
                                    <small class="opacity-75">Şeker Mah. Tatlı Sok. No:1, İstanbul</small>
                                </div>
                            </div>

                            <div class="d-flex mb-4">
                                <div class="me-3 text-warning"><i class="fa-solid fa-envelope fa-xl"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Email</h6>
                                    <small class="opacity-75">info@sekerdedektifi.com</small>
                                </div>
                            </div>

                            <div class="d-flex">
                                <div class="me-3 text-warning"><i class="fa-solid fa-phone fa-xl"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0">Telefon</h6>
                                    <small class="opacity-75">+90 555 123 45 67</small>
                                </div>
                            </div>

                            <div class="mt-5 pt-4 border-top border-secondary">
                                <div class="d-flex gap-3">
                                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="fa-brands fa-twitter"></i></a>
                                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="fa-brands fa-instagram"></i></a>
                                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="fa-brands fa-youtube"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7 p-5 bg-white">
                        <form id="iletisimFormu">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">ADINIZ SOYADINIZ</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                                        <input type="text" name="ad" class="form-control bg-light border-start-0 shadow-none" placeholder="Adınız" required 
                                               value="<?php echo isset($_SESSION['uye_adi']) ? $_SESSION['uye_adi'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">E-POSTA ADRESİNİZ</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                                        <input type="email" name="email" class="form-control bg-light border-start-0 shadow-none" placeholder="ornek@mail.com" required
                                               value="<?php echo isset($_SESSION['uye_email']) ? $_SESSION['uye_email'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">KONU</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-tag text-muted"></i></span>
                                        <select name="konu" class="form-select bg-light border-start-0 shadow-none">
                                            <option value="Genel Soru">Genel Soru</option>
                                            <option value="Öneri">Öneri / İstek</option>
                                            <option value="Hata Bildirimi">Hata Bildirimi</option>
                                            <option value="İşbirliği">İşbirliği</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">MESAJINIZ</label>
                                    <textarea name="mesaj" class="form-control bg-light shadow-none" rows="5" placeholder="Mesajınızı buraya yazın..." required></textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-dark w-100 py-3 fw-bold rounded-3 shadow-sm transition-hover">
                                        <i class="fa-solid fa-paper-plane me-2"></i> GÖNDER
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('iletisimFormu').addEventListener('submit', function(e) {
    e.preventDefault(); // Sayfa yenilenmesini engelle

    const formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    const orjinalYazi = btn.innerHTML;

    // Butonu yükleniyor yap
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Gönderiliyor...';

    fetch('islemler/iletisim_gonder.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.durum === 'basarili') {
            Swal.fire({
                icon: 'success',
                title: 'Harika!',
                text: data.mesaj,
                confirmButtonColor: '#198754'
            });
            document.getElementById('iletisimFormu').reset(); // Formu temizle
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Hata',
                text: data.mesaj,
                confirmButtonColor: '#dc3545'
            });
        }
    })
    .catch(error => {
        console.error('Hata:', error);
        Swal.fire('Hata', 'Sunucu hatası oluştu.', 'error');
    })
    .finally(() => {
        // Butonu eski haline getir
        btn.disabled = false;
        btn.innerHTML = orjinalYazi;
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>