<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// EKLE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $stok_adi = $_POST['stok_adi'];
    $stok_tipi = $_POST['stok_tipi'];
    $birim = $_POST['birim'];
    $alis_fiyat = $_POST['alis_fiyat'];
    $satis_fiyat = $_POST['satis_fiyat'];
    $miktar = $_POST['miktar'];
    $aciklama = $_POST['aciklama'];

    $ekle = $baglanti->prepare("INSERT INTO stoklar (stok_adi, stok_tipi, birim, alis_fiyat, satis_fiyat, miktar, aciklama) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $ekle->execute([$stok_adi, $stok_tipi, $birim, $alis_fiyat, $satis_fiyat, $miktar, $aciklama]);
    header("Location: stoklar.php");
}

// SİL
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    $sil = $baglanti->prepare("DELETE FROM stoklar WHERE id = ?");
    $sil->execute([$id]);
    header("Location: stoklar.php");
}

// LİSTELE
$stoklar = $baglanti->query("SELECT * FROM stoklar ORDER BY stok_adi ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>📦 Stok Kartları</h2>

    <form method="POST" style="background: #f0f0f0; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
        <h3>Yeni Stok Ekle</h3>
        <input type="text" name="stok_adi" placeholder="Stok Adı" required>
        <select name="stok_tipi">
            <option value="Malzeme">Malzeme</option>
            <option value="Demirbaş">Demirbaş</option>
            <option value="Hizmet">Hizmet</option>
        </select>
        <input type="text" name="birim" placeholder="Birim (adet, kg, saat...)">
        <input type="number" step="0.01" name="alis_fiyat" placeholder="Alış Fiyatı">
        <input type="number" step="0.01" name="satis_fiyat" placeholder="Satış Fiyatı">
        <input type="number" step="0.01" name="miktar" placeholder="Mevcut Miktar" value="0">
        <textarea name="aciklama" placeholder="Açıklama"></textarea>
        <button type="submit" name="ekle">Stok Kaydet</button>
    </form>

    <h3>Stok Listesi</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Stok Adı</th>
            <th>Tip</th>
            <th>Birim</th>
            <th>Alış</th>
            <th>Satış</th>
            <th>Miktar</th>
            <th>Açıklama</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($stoklar as $s): ?>
            <tr>
                <td><?= $s['stok_adi'] ?></td>
                <td><?= $s['stok_tipi'] ?></td>
                <td><?= $s['birim'] ?></td>
                <td><?= number_format($s['alis_fiyat'], 2) ?> ₺</td>
                <td><?= number_format($s['satis_fiyat'], 2) ?> ₺</td>
                <td><?= $s['miktar'] ?></td>
                <td><?= $s['aciklama'] ?></td>
                <td><a href="?sil=<?= $s['id'] ?>" onclick="return confirm('Silinsin mi?')">🗑️</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
