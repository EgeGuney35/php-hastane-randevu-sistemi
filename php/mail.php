<?php
$ad = htmlspecialchars($_POST["name"]);
$email = htmlspecialchars($_POST["email"]);
$konu = htmlspecialchars($_POST["msg_subject"]);
$mesaj = htmlspecialchars($_POST["message"]);
$to = ""; //formun yönleneceği mail adresi

$mailHeaders = "From: $ad <$email>\r\n";
$mailHeaders .= "Reply-To: $email\r\n";

$mailIcerik = "Gönderen: $ad\n";
$mailIcerik .= "E-posta: $email\n\n";
$mailIcerik .= "Konu: $konu\n\n";
$mailIcerik .= "Mesaj:\n$mesaj";

if (mail($to, $konu, $mailIcerik, $mailHeaders)) {
    header("Location: index.html");
    exit();
} else {
    echo "E-posta gönderme başarısız. Lütfen tekrar deneyin.";
}
?>