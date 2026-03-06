<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// Ayarları çek
$ayar = $baglanti->query("SELECT * FROM ayarlar WHERE id = 1")->fetch(PDO::FETCH_ASSOC);

// Güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $site_basligi = $_POST['site_basligi'];
    $site_aciklama = $_POST['site_aciklama'];
    $eposta = $_POST['eposta'];
    $telefon = $_POST['telefon'];
    $adres = $_POST['adres'];

    $guncelle = $baglanti->prepare("UPDATE ayarlar SET site_basligi=?, site_aciklama=?, eposta=?, telefon=?, adres=? WHERE id=1");
    $guncelle->execute([$site_basligi, $site_aciklama, $eposta, $telefon, $adres]);
    header("Location: ayarlar.php");
    exit;
}
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>⚙️ Site Ayarları</h2>

    <form method="POST" style="background: #f9f9f9; padding: 20px; border-radius: 10px; max-width: 600px;">
        <label>Site Başlığı</label><br>
        <input type="text" name="site_basligi" value="<?= $ayar['site_basligi'] ?>" required><br><br>

        <label>Site Açıklaması</label><br>
        <textarea name="site_aciklama" rows="3"><?= $ayar['site_aciklama'] ?></textarea><br><br>

        <label>E-posta</label><br>
        <input type="email" name="eposta" value="<?= $ayar['eposta'] ?>"><br><br>

        <label>Telefon</label><br>
        <input type="text" name="telefon" value="<?= $ayar['telefon'] ?>"><br><br>

        <label>Adres</label><br>
        <textarea name="adres" rows="2"><?= $ayar['adres'] ?></textarea><br><br>

        <button type="submit">Güncelle</button>
    </form>
</div>
