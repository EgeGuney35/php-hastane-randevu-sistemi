<?php
require_once "baglanti.php";
session_start();

if (isset($_SESSION["yetkili"]) && $_SESSION["yetkili"] == 1) {
    if (isset($_GET['id'])) {
        $doktor_id = intval($_GET['id']);
        $delete_sql = "DELETE FROM doktorlar WHERE id = ?";
        $stmt = $baglanti->prepare($delete_sql);
        $stmt->bind_param("i", $doktor_id);

        if ($stmt->execute()) {
            mysqli_stmt_close($stmt);
            header("Location: yetkili.php?doktor_sil_mesaji=success");
            exit();
        } else {
            echo "<p style='color:red;'>Doktor silinirken bir hata oluştu!</p>";
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