<?php
session_start();
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad_soyad = $_POST['ad_soyad'];
    $kullanici_adi = $_POST['kullanici_adi'];
    $sifre = password_hash($_POST['sifre'], PASSWORD_DEFAULT);

    // Aynı kullanıcı adı var mı kontrol et
    $varmi = $baglanti->prepare("SELECT id FROM kullanicilar WHERE kullanici_adi = ?");
    $varmi->execute([$kullanici_adi]);

    if ($varmi->rowCount() > 0) {
        $hata = "Bu kullanıcı adı zaten kullanılıyor.";
    } else {
        $ekle = $baglanti->prepare("INSERT INTO kullanicilar (ad_soyad, kullanici_adi, sifre, rol) VALUES (?, ?, ?, 'kullanici')");
        $ekle->execute([$ad_soyad, $kullanici_adi, $sifre]);
        header("Location: login.php?durum=basarili");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body style="background:#f4f6f9;">
    <div style="max-width: 400px; margin: 80px auto; background: white; padding: 30px; border-radius: 10px;">
        <h2 style="text-align: center;">📝 Kayıt Ol</h2>

        <?php if (isset($hata)): ?>
            <p style="color: red;"><?= $hata ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="ad_soyad" placeholder="Ad Soyad" required>
            <input type="text" name="kullanici_adi" placeholder="Kullanıcı Adı" required>
            <input type="password" name="sifre" placeholder="Şifre" required>
            <button type="submit">Kayıt Ol</button>
        </form>
        <p style="text-align:center; margin-top: 15px;">Zaten hesabın var mı? <a href="login.php">Giriş yap</a></p>
    </div>
</body>
</html>
