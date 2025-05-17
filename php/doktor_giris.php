<?php
session_start();
require_once "baglanti.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullaniciadi = htmlspecialchars($_POST["kullaniciadi"]);
    $parola = htmlspecialchars($_POST["parola"]);
    $sql = "SELECT * FROM doktorlar WHERE kullaniciadi = ?";
    $stmt = $baglanti->prepare($sql);
    $stmt->bind_param("s", $kullaniciadi);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($parola === $user['parola']) {
            $_SESSION["doktor"] = 1;
            $_SESSION["doktor_id"] = $user['id'];
            header("Location: doktor_paneli.php");
            exit();
        } else {
            $error = "Yanlış kullanıcı adı veya parola!";
        }
    } else {
        $error = "Yanlış kullanıcı adı veya parola!";
    }
} else {
    if (isset($_SESSION["doktor"]) && $_SESSION["doktor"] == 1) {
        header("Location: doktor_paneli.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8" />
    <title>Yetkili Giriş</title>
    <link rel="stylesheet" type="text/css" href="../css/style_yetkili.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <form action="" method="post">
        <div class="logo-container">
            <img src="../img/logo.png" alt="Hastane Logosu">
        </div>
        <h2>Doktor Paneli</h2>
        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <input type="text" name="kullaniciadi" placeholder="Kullanıcı Adı" required />
        <input type="password" name="parola" placeholder="Parola" required />
        <button type="submit">Giriş Yap</button>
        <a href="randevu.php" class="ca">Geri Dön</a>
    </form>
</body>
</html>
