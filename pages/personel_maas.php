<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// Personel, kasa ve banka bilgilerini çek
$personeller = $baglanti->query("SELECT * FROM personeller ORDER BY ad_soyad ASC")->fetchAll(PDO::FETCH_ASSOC);
$kasalar = $baglanti->query("SELECT * FROM kasalar ORDER BY ad ASC")->fetchAll(PDO::FETCH_ASSOC);
$bankalar = $baglanti->query("SELECT * FROM bankalar ORDER BY ad ASC")->fetchAll(PDO::FETCH_ASSOC);

// Maaş Ekleme
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $personel_id = $_POST['personel_id'];
    $tutar = floatval($_POST['tutar']);
    $tarih = $_POST['tarih'];
    $odeme_yolu = $_POST['odeme_yolu'];
    $odeme_id = $_POST['odeme_id'];
    $aciklama = $_POST['aciklama'];

    // Önce bakiye kontrolü
    if ($odeme_yolu == 'Kasa') {
        $bakiyeSorgu = $baglanti->prepare("SELECT bakiye FROM kasalar WHERE id = ?");
    } else {
        $bakiyeSorgu = $baglanti->prepare("SELECT bakiye FROM bankalar WHERE id = ?");
    }
    $bakiyeSorgu->execute([$odeme_id]);
    $bakiye = $bakiyeSorgu->fetchColumn();

    if ($bakiye === false || $bakiye < $tutar) {
        echo "<script>alert('Yetersiz bakiye.'); window.location='personel_maas.php';</script>";
        exit;
    }

    // Maaşı kaydet
    $ekle = $baglanti->prepare("INSERT INTO personel_maas (personel_id, tutar, tarih, odeme_yolu, odeme_id, aciklama) VALUES (?, ?, ?, ?, ?, ?)");
    $ekle->execute([$personel_id, $tutar, $tarih, $odeme_yolu, $odeme_id, $aciklama]);

    // Bakiyeden düş
    if ($odeme_yolu == 'Kasa') {
        $baglanti->prepare("UPDATE kasalar SET bakiye = bakiye - ? WHERE id = ?")->execute([$tutar, $odeme_id]);
    } else {
        $baglanti->prepare("UPDATE bankalar SET bakiye = bakiye - ? WHERE id = ?")->execute([$tutar, $odeme_id]);
    }

    header("Location: personel_maas.php");
    exit;
}

// Listeleme
$maaslar = $baglanti->query("
    SELECT m.*, p.ad_soyad 
    FROM personel_maas m 
    LEFT JOIN personeller p ON m.personel_id = p.id 
    ORDER BY m.tarih DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left:240px; padding:20px;">
    <h2>💰 Personel Maaş Ödemeleri</h2>

    <form method="POST" style="background:#f9f9f9; padding:20px; border-radius:10px;">
        <h3>Yeni Maaş Ödemesi</h3>
        <select name="personel_id" required>
            <option disabled selected>Personel Seç</option>
            <?php foreach ($personeller as $p): ?>
                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['ad_soyad']) ?></option>
            <?php endforeach; ?>
        </select>

        <input type="number" step="0.01" name="tutar" placeholder="Tutar (₺)" required>
        <input type="date" name="tarih" required>

        <select name="odeme_yolu" required>
            <option disabled selected>Ödeme Yolu</option>
            <option value="Kasa">Kasa</option>
            <option value="Banka">Banka</option>
        </select>

        <select name="odeme_id" required>
            <option disabled selected>Kasa/Banka Seç</option>
            <?php foreach ($kasalar as $k): ?>
                <option value="<?= $k['id'] ?>">Kasa: <?= $k['ad'] ?> (<?= number_format($k['bakiye'],2) ?> ₺)</option>
            <?php endforeach; ?>
            <?php foreach ($bankalar as $b): ?>
                <option value="<?= $b['id'] ?>">Banka: <?= $b['ad'] ?> (<?= number_format($b['bakiye'],2) ?> ₺)</option>
            <?php endforeach; ?>
        </select>

        <textarea name="aciklama" placeholder="Açıklama (İsteğe Bağlı)"></textarea>
        <button type="submit" name="ekle">Maaş Kaydet</button>
    </form>

    <h3 style="margin-top:40px;">📋 Geçmiş Maaş Ödemeleri</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Personel</th>
            <th>Tutar</th>
            <th>Tarih</th>
            <th>Ödeme Yolu</th>
            <th>Açıklama</th>
        </tr>
        <?php foreach ($maaslar as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['ad_soyad']) ?></td>
                <td><?= number_format($m['tutar'],2) ?> ₺</td>
                <td><?= $m['tarih'] ?></td>
                <td><?= $m['odeme_yolu'] ?></td>
                <td><?= htmlspecialchars($m['aciklama']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
