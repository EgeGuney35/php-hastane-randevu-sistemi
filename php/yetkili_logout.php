<?php
session_start();
$_SESSION["yetkili"] = 0;
header("Location: yetkili_giris.php");
exit();
?>