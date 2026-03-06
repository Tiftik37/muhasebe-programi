<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// EKLE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $ad_soyad = $_POST['ad_soyad'];
    $tc = $_POST['tc'];
    $telefon = $_POST['telefon'];
    $mail = $_POST['mail'];
    $gorev = $_POST['gorev'];
    $maas = $_POST['maas'];

    $ekle = $baglanti->prepare("INSERT INTO personeller (ad_soyad, tc, telefon, mail, gorev, maas) VALUES (?, ?, ?, ?, ?, ?)");
    $ekle->execute([$ad_soyad, $tc, $telefon, $mail, $gorev, $maas]);
    header("Location: personeller.php");
}

// SİL
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    $sil = $baglanti->prepare("DELETE FROM personeller WHERE id = ?");
    $sil->execute([$id]);
    header("Location: personeller.php");
}

// LİSTELE
$personeller = $baglanti->query("SELECT * FROM personeller ORDER BY ad_soyad ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>👥 Personeller</h2>

    <form method="POST" style="background: #f0f0f0; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
        <h3>Yeni Personel Ekle</h3>
        <input type="text" name="ad_soyad" placeholder="Ad Soyad" required>
        <input type="text" name="tc" placeholder="TC Kimlik No">
        <input type="text" name="telefon" placeholder="Telefon">
        <input type="email" name="mail" placeholder="E-posta">
        <input type="text" name="gorev" placeholder="Görev">
        <input type="number" step="0.01" name="maas" placeholder="Maaş (₺)">
        <button type="submit" name="ekle">Kaydet</button>
    </form>

    <h3>Personel Listesi</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Ad Soyad</th>
            <th>TC</th>
            <th>Telefon</th>
            <th>E-posta</th>
            <th>Görev</th>
            <th>Maaş</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($personeller as $p): ?>
            <tr>
                <td><?= $p['ad_soyad'] ?></td>
                <td><?= $p['tc'] ?></td>
                <td><?= $p['telefon'] ?></td>
                <td><?= $p['mail'] ?></td>
                <td><?= $p['gorev'] ?></td>
                <td><?= number_format($p['maas'], 2) ?> ₺</td>
                <td><a href="?sil=<?= $p['id'] ?>" onclick="return confirm('Silinsin mi?')">🗑️</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
