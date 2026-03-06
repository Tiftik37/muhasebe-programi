<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// Personel listesi
$personeller = $baglanti->query("SELECT * FROM personeller ORDER BY ad_soyad ASC")->fetchAll(PDO::FETCH_ASSOC);

// EKLE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $personel_id = $_POST['personel_id'];
    $tarih = $_POST['tarih'];
    $saat = $_POST['saat'];
    $tip = $_POST['tip'];
    $aciklama = $_POST['aciklama'];

    $ekle = $baglanti->prepare("INSERT INTO personel_giris (personel_id, tarih, saat, tip, aciklama) VALUES (?, ?, ?, ?, ?)");
    $ekle->execute([$personel_id, $tarih, $saat, $tip, $aciklama]);
    header("Location: personel_giris.php");
}

$girisler = $baglanti->query("SELECT g.*, p.ad_soyad FROM personel_giris g 
    LEFT JOIN personeller p ON g.personel_id = p.id 
    ORDER BY g.tarih DESC, g.saat DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>🕒 Personel Giriş/Çıkış Takibi</h2>

    <form method="POST" style="background:#f9f9f9; padding:20px; border-radius:10px;">
        <h3>Yeni Kayıt</h3>
        <select name="personel_id" required>
            <option disabled selected>Personel Seç</option>
            <?php foreach ($personeller as $p): ?>
                <option value="<?= $p['id'] ?>"><?= $p['ad_soyad'] ?></option>
            <?php endforeach; ?>
        </select>

        <input type="date" name="tarih" value="<?= date('Y-m-d') ?>" required>
        <input type="time" name="saat" value="<?= date('H:i') ?>" required>

        <select name="tip" required>
            <option value="Giris">Giriş</option>
            <option value="Cikis">Çıkış</option>
        </select>

        <textarea name="aciklama" placeholder="Açıklama (opsiyonel)"></textarea>
        <button type="submit" name="ekle">Kaydet</button>
    </form>

    <h3 style="margin-top:30px;">Geçmiş Kayıtlar</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Personel</th>
            <th>Tarih</th>
            <th>Saat</th>
            <th>Tip</th>
            <th>Açıklama</th>
        </tr>
        <?php foreach ($girisler as $g): ?>
            <tr>
                <td><?= $g['ad_soyad'] ?></td>
                <td><?= $g['tarih'] ?></td>
                <td><?= $g['saat'] ?></td>
                <td><?= $g['tip'] == 'Giris' ? '✅ Giriş' : '❌ Çıkış' ?></td>
                <td><?= $g['aciklama'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
