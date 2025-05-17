<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Randevular</title>
    <link rel="stylesheet" href="../css/style_kullanici.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    require_once "baglanti.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["tcc"]) && strlen($_POST["tcc"]) == 11) {
            $tc = mysqli_real_escape_string($baglanti, $_POST['tcc']);

            $SQL = "SELECT r.*, d.isim_soyisim AS doktor_isim FROM randevular r 
                    LEFT JOIN doktorlar d ON r.doktor_id = d.id 
                    WHERE r.tc='$tc'";
            $result = mysqli_query($baglanti, $SQL);

            if (!$result || mysqli_num_rows($result) == 0) {
                $kayit_bulunamadi = true;
            }
        }
    } else {
        header("Location: randevu.php");
        exit();
    }
    ?>

    <div class="container">
        <h1>Randevular</h1>
        <p><a class="logout" href="randevu.php">Geri Dön</a></p>

        <?php if (isset($kayit_bulunamadi) && $kayit_bulunamadi) {
            echo "<p class='not-found-message'>Kayıt bulunamadı...</p>";
        } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>Adı Soyadı</th>
                        <th>Telefonu</th>
                        <th>Poliklinik</th>
                        <th>Doktor</th>
                        <th>T.C. Kimlik Numarası</th>
                        <th>Tarih</th>
                        <th>Saat</th>
                        <th>Şikayet</th>
                        <th>Randevu Durumu</th>
                        <th>Sil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($result)) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $randevu_tarihi = new DateTime($row['tarih'] . ' ' . $row['saat']);
                            $bugun = new DateTime();
                            $bugun_saat = new DateTime();
                            $bugun_saat->setTime($bugun_saat->format('H'), $bugun_saat->format('i'));
                            ?>
                            <tr>
                                <td><?php echo $row['isim_soyisim']; ?></td>
                                <td><?php echo $row['tel_no']; ?></td>
                                <td><?php echo $row['polikinlik']; ?></td>
                                <td><?php echo !empty($row['doktor_isim']) ? $row['doktor_isim'] : 'Belirtilmedi'; ?></td>
                                <td><?php echo $row['tc']; ?></td>
                                <td><?php echo $row['tarih']; ?></td>
                                <td><?php echo $row['saat']; ?></td>
                                <td class="sikayet"><?php echo $row['sikayet'] ? $row['sikayet'] : "Belirtilmedi"; ?></td>
                                <td>
                                    <?php if ($randevu_tarihi < $bugun) { ?>
                                        <span class="expired">RANDEVU SÜRESİ DOLDU</span>
                                    <?php } elseif ($randevu_tarihi->format('Y-m-d') == $bugun->format('Y-m-d') && $randevu_tarihi > $bugun_saat) { ?>
                                        <span class="today">RANDEVU BUGÜN</span>
                                    <?php } else { ?>
                                        <span class="upcoming">BEKLEYEN RANDEVU</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($randevu_tarihi < $bugun || ($randevu_tarihi->format('Y-m-d') == $bugun->format('Y-m-d') && $randevu_tarihi <= $bugun_saat)) { ?>
                                        <a class="delete-button disabled">Sil</a>
                                    <?php } else { ?>
                                        <a href='kullanici_randevusil.php?kayitno=<?php echo $row['id']; ?>&tc=<?php echo $row['tc']; ?>'
                                            class="delete-button">Sil</a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</body>
</html>