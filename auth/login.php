<?php
session_start();
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullanici = $_POST['kullanici'];
    $sifre = $_POST['sifre'];

    // Kullanıcıyı veritabanında ara
    $sorgu = $baglanti->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = ?");
    $sorgu->execute([$kullanici]);

    if ($sorgu->rowCount() == 1) {
        $veri = $sorgu->fetch(PDO::FETCH_ASSOC);

        if (password_verify($sifre, $veri['sifre'])) {
            // Oturumu başlat
            $_SESSION['kullanici_adi'] = $veri['kullanici_adi'];
            $_SESSION['rol'] = $veri['rol'];

            // GİRİŞ LOG KAYDI
            $ip = $_SERVER['REMOTE_ADDR'];
            $tarayici = $_SERVER['HTTP_USER_AGENT'];
            $log = $baglanti->prepare("INSERT INTO giris_loglari (kullanici_adi, ip_adresi, tarayici) VALUES (?, ?, ?)");
            $log->execute([$veri['kullanici_adi'], $ip, $tarayici]);

            // Dashboard'a yönlendir
            header("Location: ../pages/dashboard.php");
            exit;
        } else {
            $hata = "❌ Hatalı şifre.";
        }
    } else {
        $hata = "❌ Kullanıcı bulunamadı.";
    }
}
?>

<!-- Giriş Formu -->
<form method="POST" style="margin-top: 150px; text-align:center;">
    <h2>🔐 Giriş Yap</h2>
    <input type="text" name="kullanici" placeholder="Kullanıcı Adı" required><br><br>
    <input type="password" name="sifre" placeholder="Şifre" required><br><br>
    <button type="submit">Giriş</button><br><br>
    <?php if (isset($hata)) echo "<p style='color:red;'>$hata</p>"; ?>
</form>
