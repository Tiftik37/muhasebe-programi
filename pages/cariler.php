<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// EKLE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $unvan = $_POST['unvan'];
    $cari_tipi = $_POST['cari_tipi'];
    $telefon = $_POST['telefon'];
    $email = $_POST['email'];
    $adres = $_POST['adres'];
    $vergi_no = $_POST['vergi_no'];
    $vergi_dairesi = $_POST['vergi_dairesi'];

    $ekle = $baglanti->prepare("INSERT INTO cariler (unvan, cari_tipi, telefon, email, adres, vergi_no, vergi_dairesi) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $ekle->execute([$unvan, $cari_tipi, $telefon, $email, $adres, $vergi_no, $vergi_dairesi]);
    header("Location: cariler.php");
    exit;
}

// SİL
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    $sil = $baglanti->prepare("DELETE FROM cariler WHERE id = ?");
    $sil->execute([$id]);
    header("Location: cariler.php");
    exit;
}

// LİSTE
$cariler = $baglanti->query("SELECT * FROM cariler ORDER BY unvan ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>📒 Cari Hesaplar</h2>

    <form method="POST" style="background: #f2f2f2; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
        <h3>Yeni Cari Ekle</h3>
        <input type="text" name="unvan" placeholder="Unvan / Firma Adı" required>
        <select name="cari_tipi">
            <option value="Alıcı">Alıcı</option>
            <option value="Satıcı">Satıcı</option>
        </select>
        <input type="text" name="telefon" placeholder="Telefon">
        <input type="email" name="email" placeholder="E-Posta">
        <textarea name="adres" placeholder="Adres"></textarea>
        <input type="text" name="vergi_no" placeholder="Vergi No">
        <input type="text" name="vergi_dairesi" placeholder="Vergi Dairesi">
        <button type="submit" name="ekle">Cari Ekle</button>
    </form>

    <h3>Mevcut Cariler</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Unvan</th>
            <th>Tip</th>
            <th>Telefon</th>
            <th>E-posta</th>
            <th>Vergi No</th>
            <th>Vergi Dairesi</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($cariler as $c): ?>
            <tr>
                <td><?= $c['unvan'] ?></td>
                <td><?= $c['cari_tipi'] ?></td>
                <td><?= $c['telefon'] ?></td>
                <td><?= $c['email'] ?></td>
                <td><?= $c['vergi_no'] ?></td>
                <td><?= $c['vergi_dairesi'] ?></td>
                <td><a href="?sil=<?= $c['id'] ?>" onclick="return confirm('Silinsin mi?')">🗑️</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
