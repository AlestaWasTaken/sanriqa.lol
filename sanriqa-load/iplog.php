<?php
$ip = $_SERVER['REMOTE_ADDR'];
$time = date("Y-m-d H:i:s");
$logFile = "ip_log.txt";

// IP daha önce loglandı mı?
$loggedIps = file_exists($logFile) ? file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

if (!in_array($ip, $loggedIps)) {
    // Ülke bilgisi çekiliyor
    $geoInfo = @file_get_contents("http://ip-api.com/json/{$ip}");
    $geoData = json_decode($geoInfo);

    // Bilgiler mevcutsa değişkenlere ata
    $country = $geoData->country ?? "Bilinmiyor";
    $city = $geoData->city ?? "Bilinmiyor";
    $isp = $geoData->isp ?? "Bilinmiyor";

    // Mail içeriği
    $to = "salihfrei@gmail.com"; // Kendi e-posta adresin
    $subject = "Yeni Ziyaretçi IP";
    $message = "Ziyaretçi IP: $ip\nZaman: $time\nÜlke: $country\nŞehir: $city\nİnternet Sağlayıcısı: $isp";
    $headers = "From: ipbot@sanriqa.lol";

    mail($to, $subject, $message, $headers);

    // IP'yi kaydet
    file_put_contents($logFile, $ip . "\n", FILE_APPEND);
}

// Görünmez PNG çıktısı
header("Content-Type: image/png");
echo base64_decode("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/w8AAusB9UVOj7sAAAAASUVORK5CYII=");
?>
