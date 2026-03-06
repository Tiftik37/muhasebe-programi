<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// EKLEME
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $banka_adi = $_POST['banka_adi'];
    $iban = $_POST['iban'];
    $hesap_sahibi = $_POST['hesap_sahibi'];
    $bakiye = $_POST['bakiye'];

    $ekle = $baglanti->prepare("INSERT INTO bankalar (banka_adi, iban, hesap_sahibi, bakiye) VALUES (?, ?, ?, ?)");
    $ekle->execute([$banka_adi, $iban, $hesap_sahibi, $bakiye]);
    header("Location: bankalar.php");
}

// SİLME
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    $sil = $baglanti->prepare("DELETE FROM bankalar WHERE id = ?");
    $sil->execute([$id]);
    header("Location: bankalar.php");
}

// VERİLER
$bankalar = $baglanti->query("SELECT * FROM bankalar ORDER BY banka_adi ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>🏦 Banka Hesapları</h2>

    <!-- FORM -->
    <form method="POST" style="background: #f7f7f7; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
        <h3>Yeni Banka Hesabı</h3>
        <input type="text" name="banka_adi" placeholder="Banka Adı" required>
        <input type="text" name="iban" placeholder="IBAN">
        <input type="text" name="hesap_sahibi" placeholder="Hesap Sahibi">
        <input type="number" step="0.01" name="bakiye" placeholder="Başlangıç Bakiyesi" required>
        <button type="submit" name="ekle">Ekle</button>
    </form>

    <!-- TABLO -->
    <h3>Mevcut Hesaplar</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Banka</th>
            <th>IBAN</th>
            <th>Hesap Sahibi</th>
            <th>Bakiye</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($bankalar as $b): ?>
            <tr>
                <td><?= $b['banka_adi'] ?></td>
                <td><?= $b['iban'] ?></td>
                <td><?= $b['hesap_sahibi'] ?></td>
                <td><?= number_format($b['bakiye'], 2) ?> ₺</td>
                <td>
                    <a href="banka_detay.php?id=<?= $b['id'] ?>">📄 İşlemler</a> |
                    <a href="?sil=<?= $b['id'] ?>" onclick="return confirm('Bu banka silinsin mi?')">🗑️</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
