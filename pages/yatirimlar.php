<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// EKLE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $tur = $_POST['tur'];
    $miktar = $_POST['miktar'];
    $tarih = $_POST['tarih'];
    $aciklama = $_POST['aciklama'];

    $ekle = $baglanti->prepare("INSERT INTO yatirimlar (tur, miktar, tarih, aciklama) VALUES (?, ?, ?, ?)");
    $ekle->execute([$tur, $miktar, $tarih, $aciklama]);
    header("Location: yatirimlar.php");
}

// SİL
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    $sil = $baglanti->prepare("DELETE FROM yatirimlar WHERE id = ?");
    $sil->execute([$id]);
    header("Location: yatirimlar.php");
}

// LİSTE
$veriler = $baglanti->query("SELECT * FROM yatirimlar ORDER BY tarih DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>📈 Yatırım Takibi</h2>

    <!-- FORM -->
    <form method="POST" style="background: #f0f0f0; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
        <h3>Yeni Yatırım Girişi</h3>
        <input type="text" name="tur" placeholder="Yatırım Türü (Dolar, Altın, Hisse...)" required>
        <input type="number" step="0.01" name="miktar" placeholder="Miktar" required>
        <input type="date" name="tarih" required>
        <textarea name="aciklama" placeholder="Açıklama"></textarea>
        <button type="submit" name="ekle">Ekle</button>
    </form>

    <!-- LİSTE -->
    <h3>Yatırım Geçmişi</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background: #fff;">
        <tr>
            <th>Tür</th>
            <th>Miktar</th>
            <th>Tarih</th>
            <th>Açıklama</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($veriler as $y): ?>
            <tr>
                <td><?= $y['tur'] ?></td>
                <td><?= number_format($y['miktar'], 2) ?></td>
                <td><?= $y['tarih'] ?></td>
                <td><?= $y['aciklama'] ?></td>
                <td><a href="?sil=<?= $y['id'] ?>" onclick="return confirm('Silmek istediğine emin misin?')">🗑️</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
