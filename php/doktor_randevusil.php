<?php
require_once "baglanti.php";
session_start();

if (!isset($_SESSION["doktor"]) || !isset($_SESSION["doktor_id"])) {
    header("Location: doktor_giris.php");
    exit();
}

if (isset($_GET["kayitno"])) {
    $randevu_id = intval($_GET["kayitno"]);
    $sql = "DELETE FROM randevular WHERE id = ? AND doktor_id = ?";
    $stmt = $baglanti->prepare($sql);
    $stmt->bind_param("ii", $randevu_id, $_SESSION["doktor_id"]);
    if ($stmt->execute()) {
        header("Location: doktor_randevular.php");
        exit();
    } else {
        echo "Randevu silinirken bir hata oluştu.";
    }
}
?>
