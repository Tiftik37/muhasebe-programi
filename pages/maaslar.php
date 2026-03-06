<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// PERSONELLERİ ÇEK
$personeller = $baglanti->query("SELECT * FROM personeller ORDER BY ad_soyad ASC")->fetchAll(PDO::FETCH_ASSOC);

// EKLE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $personel_id = $_POST['personel_id'];
    $ay = $_POST['ay'];
    $yil = $_POST['yil'];
    $odenen_tutar = $_POST['odenen_tutar'];
    $durum = $_POST['durum'];
    $aciklama = $_POST['aciklama'];

    $ekle = $baglanti->prepare("INSERT INTO maaslar (personel_id, ay, yil, odenen_tutar, durum, aciklama) VALUES (?, ?, ?, ?, ?, ?)");
    $ekle->execute([$personel_id, $ay, $yil, $odenen_tutar, $durum, $aciklama]);
    header("Location: maaslar.php");
}

// SİL
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    $sil = $baglanti->prepare("DELETE FROM maaslar WHERE id = ?");
    $sil->execute([$id]);
    header("Location: maaslar.php");
}

// LİSTE
$veriler = $baglanti->query("
    SELECT m.*, p.ad_soyad 
    FROM maaslar m 
    JOIN personeller p ON m.personel_id = p.id 
    ORDER BY m.yil DESC, m.ay DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>💰 Maaş Ödemeleri</h2>

    <!-- FORM -->
    <form method="POST" style="background: #eef; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
        <h3>Maaş Kaydı Ekle</h3>
        <select name="personel_id" required>
            <option value="">Personel Seç</option>
            <?php foreach ($personeller as $p): ?>
                <option value="<?= $p['id'] ?>"><?= $p['ad_soyad'] ?></option>
            <?php endforeach; ?>
        </select>
        <select name="ay">
            <?php foreach (['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'] as $a): ?>
                <option value="<?= $a ?>"><?= $a ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="yil" placeholder="Yıl" value="<?= date('Y') ?>" required>
        <input type="number" step="0.01" name="odenen_tutar" placeholder="Ödenen Tutar" required>
        <select name="durum">
            <option value="Ödendi">Ödendi</option>
            <option value="Beklemede">Beklemede</option>
        </select>
        <textarea name="aciklama" placeholder="Açıklama (opsiyonel)"></textarea>
        <button type="submit" name="ekle">Kaydet</button>
    </form>

    <!-- TABLO -->
    <h3>Maaş Listesi</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Personel</th>
            <th>Ay / Yıl</th>
            <th>Ödenen Tutar</th>
            <th>Durum</th>
            <th>Açıklama</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($veriler as $m): ?>
            <tr>
                <td><?= $m['ad_soyad'] ?></td>
                <td><?= $m['ay'] ?> <?= $m['yil'] ?></td>
                <td><?= number_format($m['odenen_tutar'], 2) ?> ₺</td>
                <td><?= $m['durum'] ?></td>
                <td><?= $m['aciklama'] ?></td>
                <td><a href="?sil=<?= $m['id'] ?>" onclick="return confirm('Silinsin mi?')">🗑️</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
