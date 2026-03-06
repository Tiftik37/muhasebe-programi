<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

$hareketler = $baglanti->query("
    SELECT ch.*, c.unvan 
    FROM cari_hareketler ch
    JOIN cariler c ON ch.cari_id = c.id
    ORDER BY ch.islem_tarihi DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>📌 Cari Hareketler</h2>

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Cari</th>
            <th>Tip</th>
            <th>Tutar</th>
            <th>Açıklama</th>
            <th>Tarih</th>
        </tr>
        <?php foreach ($hareketler as $h): ?>
            <tr>
                <td><?= $h['unvan'] ?></td>
                <td style="color:<?= $h['islem_tipi'] == 'Alacak' ? 'green' : 'red' ?>; font-weight: bold;">
                    <?= $h['islem_tipi'] ?>
                </td>
                <td><?= number_format($h['tutar'], 2) ?> ₺</td>
                <td><?= $h['aciklama'] ?></td>
                <td><?= $h['islem_tarihi'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
