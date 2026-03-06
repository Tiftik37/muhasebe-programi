<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// Kasalar ve bankalar
$kasalar = $baglanti->query("SELECT * FROM kasalar")->fetchAll(PDO::FETCH_ASSOC);
$bankalar = $baglanti->query("SELECT * FROM bankalar")->fetchAll(PDO::FETCH_ASSOC);

// Fatura ekleme
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $fatura_turu = $_POST['fatura_turu'];
    $tutar = floatval($_POST['tutar']);
    $vade = $_POST['vade_tarihi'];
    $odeme_yolu = $_POST['odeme_yolu'];
    $odeme_id = $_POST['odeme_id'];
    $durum = $_POST['durum'];
    $aciklama = $_POST['aciklama'];

    // Veritabanına ekle
    $baglanti->prepare("INSERT INTO faturalar (fatura_turu, tutar, vade_tarihi, odeme_yolu, odeme_id, durum, aciklama) VALUES (?, ?, ?, ?, ?, ?, ?)")
             ->execute([$fatura_turu, $tutar, $vade, $odeme_yolu, $odeme_id, $durum, $aciklama]);

    // Ödeme yapıldıysa kasadan/bankadan düş
    if ($durum == "Ödendi") {
        if ($odeme_yolu == "Kasa") {
            $baglanti->prepare("UPDATE kasalar SET bakiye = bakiye - ? WHERE id = ?")->execute([$tutar, $odeme_id]);
        } else {
            $baglanti->prepare("UPDATE bankalar SET bakiye = bakiye - ? WHERE id = ?")->execute([$tutar, $odeme_id]);
        }
    }

    header("Location: faturalar.php");
}

// Fatura listele
$faturalar = $baglanti->query("
    SELECT f.*, 
    CASE 
        WHEN f.odeme_yolu = 'Kasa' THEN (SELECT ad FROM kasalar WHERE id = f.odeme_id)
        WHEN f.odeme_yolu = 'Banka' THEN (SELECT ad FROM bankalar WHERE id = f.odeme_id)
    END AS odeme_adi
    FROM faturalar f
    ORDER BY f.vade_tarihi ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left:240px; padding:20px;">
    <h2>📄 Faturalar</h2>

    <form method="POST" style="background:#f9f9f9; padding:20px; border-radius:10px;">
        <h3>Yeni Fatura Ekle</h3>
        <input type="text" name="fatura_turu" placeholder="Fatura Türü (Elektrik, Su...)" required>
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
            <option value="Ödenmedi">Ödenmedi</option>
            <option value="Ödendi">Ödendi</option>
        </select>

        <textarea name="aciklama" placeholder="Açıklama (opsiyonel)"></textarea>
        <button type="submit" name="ekle">Fatura Kaydet</button>
    </form>

    <h3 style="margin-top:30px;">🧾 Fatura Listesi</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Tür</th>
            <th>Tutar</th>
            <th>Vade</th>
            <th>Ödeme Yolu</th>
            <th>Kaynak</th>
            <th>Durum</th>
            <th>Açıklama</th>
        </tr>
        <?php foreach ($faturalar as $f): ?>
            <tr style="<?= $f['durum'] == 'Ödenmedi' ? 'background:#ffe5e5;' : 'background:#e5ffe5;' ?>">
                <td><?= $f['fatura_turu'] ?></td>
                <td><?= number_format($f['tutar'],2) ?> ₺</td>
                <td><?= $f['vade_tarihi'] ?></td>
                <td><?= $f['odeme_yolu'] ?></td>
                <td><?= $f['odeme_adi'] ?></td>
                <td><?= $f['durum'] ?></td>
                <td><?= htmlspecialchars($f['aciklama']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
