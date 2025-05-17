<?php
require_once "baglanti.php";
session_start();

if (!isset($_SESSION["yetkili"]) || $_SESSION["yetkili"] != 1) {
    header("Location: yetkili_giris.php");
    exit();
}
function turkish_to_ascii($str) {
    $turkish = array('ç','Ç','ğ','Ğ','ı','I','İ','ö','Ö','ş','Ş','ü','Ü');
    $ascii   = array('c','c','g','g','i','i','i','o','o','s','s','u','u');
    return str_replace($turkish, $ascii, $str);
}

$kullanici_adi_olusturuldu = false;
$olusturulan_kullanici_adi = '';
$olusturulan_sifre = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['doktor_adi']) && isset($_POST['bolum_id'])) {
    $doktor_adi = trim(htmlspecialchars($_POST['doktor_adi']));
    $bolum_id = intval($_POST['bolum_id']);

    if (!empty($doktor_adi) && $bolum_id > 0) {
        $kullaniciadi = turkish_to_ascii(strtolower(str_replace(' ', '', $doktor_adi)));
        $orijinal_kullaniciadi = $kullaniciadi;
        $sayac = 1;
        while (true) {
            $kontrol_sql = "SELECT id FROM doktorlar WHERE kullaniciadi = ?";
            $stmt = $baglanti->prepare($kontrol_sql);
            $stmt->bind_param("s", $kullaniciadi);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                break;
            } else {
                $kullaniciadi = $orijinal_kullaniciadi . $sayac;
                $sayac++;
            }
        }

        $parola = '';
        for ($i = 0; $i < 6; $i++) {
            $parola .= rand(0, 9);
        }

        $insert_sql = "INSERT INTO doktorlar (isim_soyisim, bolum_id, kullaniciadi, parola, create_date) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $baglanti->prepare($insert_sql);
        $stmt->bind_param("siss", $doktor_adi, $bolum_id, $kullaniciadi, $parola);

        if ($stmt->execute()) {
            echo "<p class='message success'>Doktor başarıyla eklendi!</p>";
            $kullanici_adi_olusturuldu = true;
            $olusturulan_kullanici_adi = $kullaniciadi;
            $olusturulan_sifre = $parola;
        } else {
            echo "<p class='message error'>Doktor eklenirken bir hata oluştu!</p>";
        }
    } else {
        echo "<p class='message error'>Lütfen tüm alanları doldurun!</p>";
    }
}

$SQL = "SELECT r.*, d.isim_soyisim AS doktor_isim FROM randevular r 
LEFT JOIN doktorlar d ON r.doktor_id = d.id 
ORDER BY r.id";

$result = mysqli_query($baglanti, $SQL);
if (!$result || mysqli_num_rows($result) == 0) {
    $randevu_bulunamadi = true;
}

$doktor_sql = "SELECT d.id, d.isim_soyisim, p.ad AS bolum_ad, d.create_date 
FROM doktorlar d 
JOIN poliklinikler p ON d.bolum_id = p.id 
ORDER BY d.create_date DESC";
$doktor_result = mysqli_query($baglanti, $doktor_sql);
if (!$doktor_result || mysqli_num_rows($doktor_result) == 0) {
    $doktor_bulunamadi = true;
}

if (isset($_GET['sil_mesaji']) && $_GET['sil_mesaji'] == "success") {
    echo "<p class='message success'>Randevu başarıyla silindi!</p>";
}

if (isset($_GET['doktor_sil_mesaji']) && $_GET['doktor_sil_mesaji'] == "success") {
    echo "<p class='message success'>Doktor başarıyla silindi!</p>";
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Yetkili Paneli</title>
    <link rel="stylesheet" href="../css/yeni_yetkili_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Yetkili Paneli</h1>

        <div class="logout">
            <a href="yetkili_logout.php">Oturumu Kapat</a>
        </div>

        <h2>Randevular</h2>
        <?php if (isset($randevu_bulunamadi) && $randevu_bulunamadi) { ?>
            <p class="not-found-message">Kayıt bulunamadı...</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>Sıra No</th>
                        <th>Adı Soyadı</th>
                        <th>Telefon</th>
                        <th>Poliklinik</th>
                        <th>Doktor</th>
                        <th>T.C. Kimlik No</th>
                        <th>Tarih</th>
                        <th>Saat</th>
                        <th>Şikayet</th>
                        <th>Sil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['isim_soyisim']); ?></td>
                            <td><?php echo htmlspecialchars($row['tel_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['polikinlik']); ?></td>
                            <td><?php echo htmlspecialchars($row['doktor_isim'] ? $row['doktor_isim'] : 'Belirtilmedi'); ?></td>
                            <td><?php echo htmlspecialchars($row['tc']); ?></td>
                            <td><?php echo htmlspecialchars($row['tarih']); ?></td>
                            <td><?php echo htmlspecialchars($row['saat']); ?></td>
                            <td class="sikayet"><?php echo htmlspecialchars($row['sikayet'] ? $row['sikayet'] : 'Belirtilmedi'); ?></td>
                            <td><a href='yetkili_randevusil.php?kayitno=<?php echo $row['id']; ?>'>Sil</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>

        <div class="doctor-panel">
            <h2>Doktor Ekle / Sil</h2>
            <form method="post" action="">
                <input type="text" name="doktor_adi" placeholder="Doktor Adı Soyadı" required>
                <select name="bolum_id" required>
                    <option value="" selected disabled>Bölüm Seçiniz</option>
                    <?php
                    $bolum_sql = "SELECT * FROM poliklinikler";
                    $bolum_result = mysqli_query($baglanti, $bolum_sql);
                    while ($bolum_row = mysqli_fetch_assoc($bolum_result)) {
                        echo '<option value="' . $bolum_row['id'] . '">' . htmlspecialchars($bolum_row['ad']) . '</option>';
                    }
                    ?>
                </select>
                <button type="submit">Doktor Ekle</button>
            </form>

            <?php if ($kullanici_adi_olusturuldu): ?>
                <div class="credentials">
                    <h3>Oluşturulan Doktor Bilgileri:</h3>
                    <p><strong>Kullanıcı Adı:</strong> <?php echo htmlspecialchars($olusturulan_kullanici_adi); ?></p>
                    <p><strong>Şifre:</strong> <?php echo htmlspecialchars($olusturulan_sifre); ?></p>
                </div>
            <?php endif; ?>

            <h3>Mevcut Doktorlar</h3>
            <?php if (isset($doktor_bulunamadi) && $doktor_bulunamadi) { ?>
                <p class="not-found-message">Kayıt bulunamadı...</p>
            <?php } else { ?>
                <table class="doctor-table">
                    <thead>
                        <tr>
                            <th>Doktor Adı Soyadı</th>
                            <th>Bölüm</th>
                            <th>Eklenme Tarihi</th>
                            <th>Sil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($doktor_row = mysqli_fetch_assoc($doktor_result)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($doktor_row['isim_soyisim'] ? $doktor_row['isim_soyisim'] : "Belirtilmedi"); ?></td>
                                <td><?php echo htmlspecialchars($doktor_row['bolum_ad'] ? $doktor_row['bolum_ad'] : "Belirtilmedi"); ?></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($doktor_row['create_date'])); ?></td>
                                <td><a href="doktor_sil.php?id=<?php echo $doktor_row['id']; ?>">Sil</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</body>
</html>
