<?php
require_once "baglanti.php";
session_start();

if (!isset($_SESSION["doktor"]) || !isset($_SESSION["doktor_id"])) {
    header("Location: doktor_giris.php");
    exit();
}

$doktor_id = $_SESSION["doktor_id"];
$randevu_sql = "SELECT * FROM randevular WHERE doktor_id = ? ORDER BY tarih DESC, saat DESC";
$stmt = $baglanti->prepare($randevu_sql);
$stmt->bind_param("i", $doktor_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Randevularım</title>
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
            <h1>Randevularım</h1>
            <?php if ($result->num_rows == 0) { ?>
                <p class="not-found-message">Kayıt bulunamadı...</p>
            <?php } else { ?>
                <table class="randevu-table">
                    <thead>
                        <tr>
                            <th>Adı Soyadı</th>
                            <th>Telefon</th>
                            <th>T.C. Kimlik No</th>
                            <th>Tarih</th>
                            <th>Saat</th>
                            <th>Şikayet</th>
                            <th>Sil</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['isim_soyisim']); ?></td>
                                <td><?php echo htmlspecialchars(string: $row['tel_no']); ?></td>
                                <td><?php echo htmlspecialchars($row['tc']); ?></td>
                                <td><?php echo htmlspecialchars($row['tarih']); ?></td>
                                <td><?php echo htmlspecialchars($row['saat']); ?></td>
                                <td><?php echo htmlspecialchars($row['sikayet']) ? htmlspecialchars($row['sikayet']) : 'Belirtilmedi'; ?></td>
                                <td>
                                    <?php
                                    $randevu_tarihi = new DateTime($row['tarih']);
                                    $bugun = new DateTime();
                                    if ($randevu_tarihi > $bugun) { ?>
                                        <a href='doktor_randevusil.php?kayitno=<?php echo $row['id']; ?>' class="btn btn-danger">Sil</a>
                                    <?php } else {
                                        echo "<span class='expired'>Geçmiş Randevu</span>";
                                    } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</body>
</html>
