<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// EKLE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $hedef_adi = $_POST['hedef_adi'];
    $hedef_miktar = $_POST['hedef_miktar'];
    $mevcut_miktar = $_POST['mevcut_miktar'];
    $hedef_tarih = $_POST['hedef_tarih'];
    $aciklama = $_POST['aciklama'];

    $ekle = $baglanti->prepare("INSERT INTO tasarruflar (hedef_adi, hedef_miktar, mevcut_miktar, hedef_tarih, aciklama) VALUES (?, ?, ?, ?, ?)");
    $ekle->execute([$hedef_adi, $hedef_miktar, $mevcut_miktar, $hedef_tarih, $aciklama]);
    header("Location: tasarruflar.php");
}

// SİL
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    $sil = $baglanti->prepare("DELETE FROM tasarruflar WHERE id = ?");
    $sil->execute([$id]);
    header("Location: tasarruflar.php");
}

// LİSTE
$veriler = $baglanti->query("SELECT * FROM tasarruflar ORDER BY hedef_tarih ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>💰 Tasarruf Hedefleri</h2>

    <!-- EKLEME FORMU -->
    <form method="POST" style="background: #eef; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
        <h3>Yeni Hedef Ekle</h3>
        <input type="text" name="hedef_adi" placeholder="Hedef Adı (örnek: Araba, Tatil...)" required>
        <input type="number" step="0.01" name="hedef_miktar" placeholder="Hedef Miktar" required>
        <input type="number" step="0.01" name="mevcut_miktar" placeholder="Mevcut Miktar" value="0">
        <input type="date" name="hedef_tarih">
        <textarea name="aciklama" placeholder="Açıklama (opsiyonel)"></textarea>
        <button type="submit" name="ekle">Hedef Oluştur</button>
    </form>

    <!-- LİSTE -->
    <h3>Mevcut Hedefler</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background: #fff;">
        <tr>
            <th>Hedef</th>
            <th>Toplam</th>
            <th>Mevcut</th>
            <th>Oran</th>
            <th>Tarih</th>
            <th>Açıklama</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($veriler as $t): 
            $oran = $t['hedef_miktar'] > 0 ? round(($t['mevcut_miktar'] / $t['hedef_miktar']) * 100, 2) : 0;
        ?>
            <tr>
                <td><?= $t['hedef_adi'] ?></td>
                <td><?= number_format($t['hedef_miktar'], 2) ?> ₺</td>
                <td><?= number_format($t['mevcut_miktar'], 2) ?> ₺</td>
                <td><?= $oran ?>%</td>
                <td><?= $t['hedef_tarih'] ?></td>
                <td><?= $t['aciklama'] ?></td>
                <td><a href="?sil=<?= $t['id'] ?>" onclick="return confirm('Bu hedefi silmek istediğine emin misin?')">🗑️</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
