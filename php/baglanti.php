<?php
    define("host", "localhost");
    define("user", "root");
    define("pass", "");
    define("db_sec", "hastane");
    define("db_port", 3306);
    $baglanti = mysqli_connect(host, user, pass, db_sec, db_port);
    $temp = mysqli_query($baglanti, "set names 'utf8'");
    if (!$baglanti) {
        die("Veritabanı bağlantı hatası: " . mysqli_connect_error());
    }
?>
