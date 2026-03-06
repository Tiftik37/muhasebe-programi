<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// Kasalar ve bankalar
$kasalar = $baglanti->query("SELECT * FROM kasalar ORDER BY ad ASC")->fetchAll(PDO::FETCH_ASSOC);
$bankalar = $baglanti->query("SELECT * FROM bankalar ORDER BY banka_adi ASC")->fetchAll(PDO::FETCH_ASSOC);

// Ekleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $tur = $_POST['tur']; // Gelir veya Gider
    $odeme_yolu = $_POST['odeme_yolu']; // Kasa veya Banka
    $odeme_id = $_POST['kaynak_id']; // DÜZELTİLDİ!
    $tutar = floatval($_POST['tutar']);
    $tarih = $_POST['tarih'];
    $aciklama = $_POST['aciklama'];

    // Veritabanına kayıt
    $baglanti->prepare("INSERT INTO gelir_gider (tur, odeme_yolu, odeme_id, tutar, tarih, aciklama) VALUES (?, ?, ?, ?, ?, ?)")
             ->execute([$tur, $odeme_yolu, $odeme_id, $tutar, $tarih, $aciklama]);

    // Bakiye güncelle
    if ($odeme_yolu == 'Kasa') {
        if ($tur == 'Gelir') {
            $baglanti->prepare("UPDATE kasalar SET bakiye = bakiye + ? WHERE id = ?")->execute([$tutar, $odeme_id]);
        } else {
            $baglanti->prepare("UPDATE kasalar SET bakiye = bakiye - ? WHERE id = ?")->execute([$tutar, $odeme_id]);
        }
    } elseif ($odeme_yolu == 'Banka') {
        if ($tur == 'Gelir') {
            $baglanti->prepare("UPDATE bankalar SET bakiye = bakiye + ? WHERE id = ?")->execute([$tutar, $odeme_id]);
        } else {
            $baglanti->prepare("UPDATE bankalar SET bakiye = bakiye - ? WHERE id = ?")->execute([$tutar, $odeme_id]);
        }
    }

    header("Location: gelir_gider.php");
    exit;
}

// Listeleme
$veriler = $baglanti->query("
    SELECT g.*, 
    CASE 
        WHEN g.odeme_yolu = 'Kasa' THEN (SELECT ad FROM kasalar WHERE id = g.odeme_id)
        WHEN g.odeme_yolu = 'Banka' THEN (SELECT banka_adi FROM bankalar WHERE id = g.odeme_id)
    END AS odeme_adi
    FROM gelir_gider g
    ORDER BY g.tarih DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left:240px; padding:20px;">
    <h2>💰 Gelir / Gider Kayıtları</h2>

    <form method="POST" style="background:#f0f0f0; padding:20px; border-radius:10px;">
        <h3>Yeni Kayıt</h3>
        <select name="tur" required>
            <option disabled selected>Tür Seç</option>
            <option value="Gelir">Gelir (+)</option>
            <option value="Gider">Gider (-)</option>
        </select>

        <select name="odeme_yolu" required>
            <option disabled selected>Ödeme Yolu</option>
            <option value="Kasa">Kasa</option>
            <option value="Banka">Banka</option>
        </select>

        <select name="kaynak_id" required>
            <option disabled selected>Kasa/Banka Seç</option>
            <?php foreach ($kasalar as $k): ?>
                <option value="<?= $k['id'] ?>">Kasa: <?= htmlspecialchars($k['ad']) ?> (<?= number_format($k['bakiye'], 2) ?> ₺)</option>
            <?php endforeach; ?>
            <?php foreach ($bankalar as $b): ?>
                <option value="<?= $b['id'] ?>">Banka: <?= htmlspecialchars($b['banka_adi']) ?> (<?= number_format($b['bakiye'], 2) ?> ₺)</option>
            <?php endforeach; ?>
        </select>

        <input type="number" step="0.01" name="tutar" placeholder="Tutar ₺" required>
        <input type="date" name="tarih" required>
        <textarea name="aciklama" placeholder="Açıklama"></textarea>
        <button type="submit" name="ekle">Kaydet</button>
    </form>

    <h3 style="margin-top:30px;">📄 Kayıtlı Gelir / Giderler</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:white;">
        <tr>
            <th>Tür</th>
            <th>Ödeme Yolu</th>
            <th>Kaynak</th>
            <th>Tutar</th>
            <th>Tarih</th>
            <th>Açıklama</th>
        </tr>
        <?php foreach ($veriler as $v): ?>
            <tr>
                <td><?= $v['tur'] ?></td>
                <td><?= $v['odeme_yolu'] ?></td>
                <td><?= $v['odeme_adi'] ?></td>
                <td><?= number_format($v['tutar'], 2) ?> ₺</td>
                <td><?= $v['tarih'] ?></td>
                <td><?= htmlspecialchars($v['aciklama']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
