<?php
session_start();
session_destroy();
header("Location: doktor_giris.php");
exit();
?>
