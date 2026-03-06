<?php
$host = "localhost";
$dbname = "u289106828_muhasebe";
$username = "u289106828_muhasebe";
$password = "123ASDWbjk.";

try {
    $baglanti = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $baglanti->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}
?>
