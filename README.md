🏥 PHP Hastane Randevu Sistemi
Bu proje, PHP ve MySQL kullanılarak geliştirilmiş basit bir hastane randevu sistemidir. Doktorlar, hastalar ve yetkililer için ayrı giriş ve kontrol panelleri içerir. Randevu oluşturma, listeleme, silme ve e-posta gönderme gibi temel işlemleri destekler.

🚀 Özellikler
Hasta için randevu alma ve sorgulama

Doktor panelinden randevuların listelenmesi

Yetkili (asistan) tarafından doktor ve randevu yönetimi

Randevu saat kontrolü ve silme işlemleri

Mail gönderimi (mail.php)

Veritabanı bağlantısı (baglanti.php)

Modern ve işlevsel PHP sayfa yapısı (MVC kullanılmadan prosedürel)

📁 Dosya Yapısı (Seçmeler)
Dosya Adı	Açıklama
randevu.php	Randevu alma formu
doktor_paneli.php	Doktor kontrol paneli
doktor_randevular.php	Doktorun randevularını görüntüleme
yetkili_randevusil.php	Yetkili tarafından randevu silme
randevu_saat_kontrol.php	Randevu saatlerinin doluluk kontrolü
kullanici_randevusil.php	Hasta tarafından randevu iptali
mail.php	Mail gönderim fonksiyonu
baglanti.php	Veritabanı bağlantısı

🧰 Kullanılan Teknolojiler
PHP (procedural)

MySQL

HTML / CSS / Bootstrap

cPanel uyumlu yapı

🔧 Kurulum Talimatları
Projeyi indirin veya klonlayın:

git clone https://github.com/EgeGuney35/php-hastane-randevu-sistemi.git

Veritabanını kurun:

phpmyadmin veya benzeri aracı kullanın.

Yeni bir veritabanı oluşturun, örn: hastane_db

Verilen .sql yedeği içe aktarın veya baglanti.php dosyasındaki tablo isimlerini kullanarak elle oluşturun.

Sunucunuza yükleyin:

htdocs klasörüne (XAMPP) ya da cPanel dosya yöneticisine tüm dosyaları atın.

baglanti.php içindeki veritabanı bilgilerini güncelleyin.

🔐 Giriş Paneli Örnekleri
Hasta
Randevu alma: randevu.php

Doktor
Giriş: doktor_giris.php

Panel: doktor_paneli.php

Yetkili (asistan)
Giriş: yetkili_giris.php

Yönetim: yetkili_randevusil.php, doktor_listesi.php

📄 Lisans
Bu proje kişisel, eğitimsel ve küçük işletme amaçlarıyla serbestçe kullanılabilir.
