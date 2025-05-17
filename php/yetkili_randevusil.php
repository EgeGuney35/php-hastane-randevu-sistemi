<?php
require_once "baglanti.php";
session_start();

if (isset($_SESSION["yetkili"]) && $_SESSION["yetkili"] == 1) {
    if (isset($_GET["kayitno"])) {
        $KAYITNO = intval($_GET["kayitno"]);
        $SQL = "DELETE FROM randevular WHERE id = ?";
        $stmt = mysqli_prepare($baglanti, $SQL);
        mysqli_stmt_bind_param($stmt, "i", $KAYITNO);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: yetkili.php?sil_mesaji=success");
            exit();
        } else {
            echo "<p style='color:red;'>Kayıt silinirken bir hata oluştu!</p>";
        }
        mysqli_stmt_close($stmt);
    } else {
        header("Location: yetkili.php");
        exit();
    }
} else {
    header("Location: yetkili.php");
    exit();
}
?>