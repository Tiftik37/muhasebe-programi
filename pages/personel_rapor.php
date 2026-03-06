<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// Filtreleme için tarih aralığı alınır
$baslangic = $_GET['baslangic'] ?? date('Y-m-01');
$bitis = $_GET['bitis'] ?? date('Y-m-d');

// Giriş verileri çekilir
$sorgu = $baglanti->prepare("
    SELECT p.ad_soyad, g.tarih, g.giris_saati, g.cikis_saati 
    FROM personel_giris g
    JOIN personeller p ON g.personel_id = p.id
    WHERE g.tarih BETWEEN ? AND ?
    ORDER BY g.tarih DESC
");
$sorgu->execute([$baslangic, $bitis]);
$veriler = $sorgu->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>📋 Personel Giriş-Çıkış Raporu</h2>

    <form method="GET" style="margin-bottom: 20px;">
        <label>Başlangıç Tarihi: <input type="date" name="baslangic" value="<?= $baslangic ?>"></label>
        <label>Bitiş Tarihi: <input type="date" name="bitis" value="<?= $bitis ?>"></label>
        <button type="submit">Filtrele</button>
    </form>

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Personel</th>
            <th>Tarih</th>
            <th>Giriş</th>
            <th>Çıkış</th>
        </tr>
        <?php foreach ($veriler as $v): ?>
        <tr>
            <td><?= $v['ad_soyad'] ?></td>
            <td><?= $v['tarih'] ?></td>
            <td><?= $v['giris_saati'] ?></td>
            <td><?= $v['cikis_saati'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
