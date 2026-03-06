<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

$kasalar = $baglanti->query("SELECT * FROM kasalar")->fetchAll(PDO::FETCH_ASSOC);
$bankalar = $baglanti->query("SELECT * FROM bankalar")->fetchAll(PDO::FETCH_ASSOC);

// EKLE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $borc_turu = $_POST['borc_turu'];
    $tutar = floatval($_POST['tutar']);
    $vade = $_POST['vade_tarihi'];
    $odeme_yolu = $_POST['odeme_yolu'];
    $odeme_id = $_POST['odeme_id'];
    $durum = $_POST['durum'];
    $aciklama = $_POST['aciklama'];

    // Ekle
    $baglanti->prepare("INSERT INTO borclar (borc_turu, tutar, vade_tarihi, odeme_yolu, odeme_id, durum, aciklama) VALUES (?, ?, ?, ?, ?, ?, ?)")
             ->execute([$borc_turu, $tutar, $vade, $odeme_yolu, $odeme_id, $durum, $aciklama]);

    // Ödendiyse kasadan/bankadan düş
    if ($durum == 'Ödendi') {
        if ($odeme_yolu == 'Kasa') {
            $baglanti->prepare("UPDATE kasalar SET bakiye = bakiye - ? WHERE id = ?")->execute([$tutar, $odeme_id]);
        } else {
            $baglanti->prepare("UPDATE bankalar SET bakiye = bakiye - ? WHERE id = ?")->execute([$tutar, $odeme_id]);
        }
    }

    header("Location: borclar.php");
}

// Listele
$borclar = $baglanti->query("
    SELECT b.*,
    CASE 
        WHEN b.odeme_yolu = 'Kasa' THEN (SELECT ad FROM kasalar WHERE id = b.odeme_id)
        WHEN b.odeme_yolu = 'Banka' THEN (SELECT ad FROM bankalar WHERE id = b.odeme_id)
    END AS odeme_adi
    FROM borclar b
    ORDER BY b.vade_tarihi ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left:240px; padding:20px;">
    <h2>📉 Borç Takibi</h2>

    <form method="POST" style="background:#f2f2f2; padding:20px; border-radius:10px;">
        <h3>Yeni Borç Ekle</h3>
        <input type="text" name="borc_turu" placeholder="Borç Türü (Kredi Kartı, Taksit...)" required>
        <input type="number" step="0.01" name="tutar" placeholder="Tutar ₺" required>
        <input type="date" name="vade_tarihi" required>

        <select name="odeme_yolu" required>
            <option disabled selected>Ödeme Yolu</option>
            <option value="Kasa">Kasa</option>
            <option value="Banka">Banka</option>
        </select>

        <select name="odeme_id" required>
            <option disabled selected>Kasa/Banka Seç</option>
            <?php foreach ($kasalar as $k): ?>
                <option value="<?= $k['id'] ?>">Kasa: <?= $k['ad'] ?></option>
            <?php endforeach; ?>
            <?php foreach ($bankalar as $b): ?>
                <option value="<?= $b['id'] ?>">Banka: <?= $b['ad'] ?></option>
            <?php endforeach; ?>
        </select>

        <select name="durum" required>
            <option value="Bekliyor">Bekliyor</option>
            <option value="Ödendi">Ödendi</option>
        </select>

        <textarea name="aciklama" placeholder="Açıklama (Opsiyonel)"></textarea>
        <button type="submit" name="ekle">Kaydet</button>
    </form>

    <h3 style="margin-top:30px;">🧾 Borç Listesi</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Borç Türü</th>
            <th>Tutar</th>
            <th>Vade</th>
            <th>Ödeme Yolu</th>
            <th>Kaynak</th>
            <th>Durum</th>
            <th>Açıklama</th>
        </tr>
        <?php foreach ($borclar as $b): ?>
            <tr style="<?= $b['durum'] == 'Bekliyor' ? 'background:#fff3cd;' : 'background:#e2ffe2;' ?>">
                <td><?= $b['borc_turu'] ?></td>
                <td><?= number_format($b['tutar'],2) ?> ₺</td>
                <td><?= $b['vade_tarihi'] ?></td>
                <td><?= $b['odeme_yolu'] ?></td>
                <td><?= $b['odeme_adi'] ?></td>
                <td><?= $b['durum'] ?></td>
                <td><?= htmlspecialchars($b['aciklama']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
