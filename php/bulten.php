<?php
require_once "baglanti.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : "";
    if (empty($email)) {
        header("Location: index.html");
        exit();
    } else {
        bulten($email);
        header("Location: index.html");
        exit();
    }
}
function bulten($email)
{
    global $baglanti;
    $sql = "INSERT INTO bulten (eposta) VALUES (?)";
    $stmt = $baglanti->prepare($sql);
    $stmt->bind_param("s", $email);
    return $stmt->execute();
}
?>