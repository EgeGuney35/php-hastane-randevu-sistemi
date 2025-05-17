<?php
require_once "baglanti.php";

if (isset($_GET['kayitno']) && isset($_GET['tc'])) {
    $kayitno = intval($_GET['kayitno']);
    $tc = mysqli_real_escape_string($baglanti, $_GET['tc']);
    $SQL = "DELETE FROM randevular WHERE id = ? AND tc = ?";
    $stmt = mysqli_prepare($baglanti, $SQL);
    mysqli_stmt_bind_param($stmt, "is", $kayitno, $tc);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: randevu_sorgula.php?sil_mesaji=success");
    } else {
        echo "<p style='color:red;'>Randevu silinirken bir hata oluştu!</p>";
    }

    mysqli_stmt_close($stmt);
} else {
    header("Location: randevu_sorgula.php");
    exit();
}
?>