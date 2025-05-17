<?php
require_once "baglanti.php";
session_start();
if (!isset($_SESSION["doktor"]) || !isset($_SESSION["doktor_id"])) {
    header("Location: doktor_giris.php");
    exit();
}

$doktor_id = $_SESSION["doktor_id"];
$doktor_sql = "SELECT d.*, p.ad AS bolum_ad FROM doktorlar d 
               LEFT JOIN poliklinikler p ON d.bolum_id = p.id 
               WHERE d.id = ?";
$stmt = $baglanti->prepare($doktor_sql);
$stmt->bind_param("i", $doktor_id);
$stmt->execute();
$doktor_result = $stmt->get_result()->fetch_assoc();

if (!$doktor_result) {
    echo "Doktor bilgisi bulunamadı.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefon = isset($_POST["telefon"]) ? $_POST["telefon"] : "";
    $eski_sifre = isset($_POST["eski_sifre"]) ? $_POST["eski_sifre"] : "";
    $yeni_sifre = isset($_POST["yeni_sifre"]) ? $_POST["yeni_sifre"] : "";
    $telefon_digits = preg_replace("/[^0-9]/", "", $telefon);
    if (preg_match("/^0[0-9]{10}$/", $telefon_digits)) {
        $telefon_formatted = "0 (" . substr($telefon_digits, 1, 3) . ") " . substr($telefon_digits, 4, 3) . " " . substr($telefon_digits, 7, 2) . " " . substr($telefon_digits, 9, 2);
        $telefon_degisikligi = ($telefon_formatted !== $doktor_result['telefon']);
        $parola_degisikligi = false;

        if (!empty($eski_sifre) && !empty($yeni_sifre)) {
            if ($eski_sifre === $doktor_result['parola']) {
                if ($yeni_sifre !== $doktor_result['parola']) {
                    $parola_degisikligi = true;
                } else {
                    $error_message = "Yeni şifre eski şifre ile aynı olamaz.";
                }
            } else {
                $error_message = "Eski şifre yanlış.";
            }
        } elseif (!empty($eski_sifre) || !empty($yeni_sifre)) {
            $error_message = "Şifreyi değiştirmek için hem eski hem de yeni şifreyi giriniz.";
        }

        if (!isset($error_message)) {
            if ($telefon_degisikligi || $parola_degisikligi) {
                if ($telefon_degisikligi && $parola_degisikligi) {
                    $update_sql = "UPDATE doktorlar SET telefon = ?, parola = ? WHERE id = ?";
                    $update_stmt = $baglanti->prepare($update_sql);
                    $update_stmt->bind_param("ssi", $telefon_formatted, $yeni_sifre, $doktor_id);
                } elseif ($telefon_degisikligi) {
                    $update_sql = "UPDATE doktorlar SET telefon = ? WHERE id = ?";
                    $update_stmt = $baglanti->prepare($update_sql);
                    $update_stmt->bind_param("si", $telefon_formatted, $doktor_id);
                } elseif ($parola_degisikligi) {
                    $update_sql = "UPDATE doktorlar SET parola = ? WHERE id = ?";
                    $update_stmt = $baglanti->prepare($update_sql);
                    $update_stmt->bind_param("si", $yeni_sifre, $doktor_id);
                }

                if (isset($update_stmt) && $update_stmt->execute()) {
                    $success_message = "Profil başarıyla güncellendi.";
                    $stmt = $baglanti->prepare($doktor_sql);
                    $stmt->bind_param("i", $doktor_id);
                    $stmt->execute();
                    $doktor_result = $stmt->get_result()->fetch_assoc();

                } else {
                    $error_message = "Profil güncellenirken bir hata oluştu.";
                }
            } else {
                $info_message = "Herhangi bir değişiklik yapılmadı.";
            }
        }
    } else {
        $error_message = "Lütfen geçerli bir telefon numarası giriniz.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doktor Paneli</title>
    <link rel="stylesheet" href="../css/doktor_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <img src="../img/logo.png" alt="Hastane Logosu">
            </div>
            <nav>
                <ul>
                    <li><a href="doktor_paneli.php">Ana Sayfa</a></li>
                    <li><a href="doktor_randevular.php">Randevularım</a></li>
                    <li><a href="doktor_logout.php">Çıkış Yap</a></li>
                </ul>
            </nav>
        </div>

        <div class="content">
            <h2>Doktor Paneli v1</h2>
            <h1>Hoşgeldiniz, <?php echo $doktor_result['isim_soyisim']; ?>!</h1>

            <?php if (isset($success_message)): ?>
                <p class="message success"><?php echo $success_message; ?></p>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <p class="message error"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <?php if (isset($info_message)): ?>
                <p class="message info"><?php echo $info_message; ?></p>
            <?php endif; ?>

            <form method="post" action="">
                <div class="form-group">
                    <label for="isim_soyisim">İsim Soyisim</label>
                    <input type="text" id="isim_soyisim" value="<?php echo $doktor_result['isim_soyisim']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="bolum">Bölüm</label>
                    <input type="text" id="bolum" value="<?php echo $doktor_result['bolum_ad']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="kullaniciadi">Kullanıcı Adı</label>
                    <input type="text" id="kullaniciadi" value="<?php echo $doktor_result['kullaniciadi']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="telefon">Telefon</label>
                    <input type="text" id="telefon" name="telefon" maxlength="17"
                        value="<?php echo htmlspecialchars($doktor_result['telefon'] ? $doktor_result['telefon'] : "Belirtilmedi"); ?>">
                </div>
                <div class="form-group">
                    <label for="eski_sifre">Eski Şifre</label>
                    <input type="password" id="eski_sifre" name="eski_sifre">
                </div>
                <div class="form-group">
                    <label for="yeni_sifre">Yeni Şifre</label>
                    <input type="password" id="yeni_sifre" name="yeni_sifre">
                </div>
                <button type="submit" class="btn">Profili Güncelle</button>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var telefonInput = document.getElementById('telefon');

        telefonInput.addEventListener('input', function(e) {
            var value = telefonInput.value;
            value = value.replace(/[^\d]/g, '');
            value = value.substring(0, 11);

            var formattedValue = '';

            if (value.length > 0) {
                formattedValue += '0';
            }
            if (value.length > 1) {
                formattedValue += ' (' + value.substring(1, 4);
            }
            if (value.length >= 4) {
                formattedValue += ') ' + value.substring(4, 7);
            }
            if (value.length >= 7) {
                formattedValue += ' ' + value.substring(7, 9);
            }
            if (value.length >= 9) {
                formattedValue += ' ' + value.substring(9, 11);
            }

            telefonInput.value = formattedValue;
        });
    });
    </script>
</body>
</html>
