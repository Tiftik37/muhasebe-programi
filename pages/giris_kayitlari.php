<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

$loglar = $baglanti->query("SELECT * FROM giris_loglari ORDER BY giris_zamani DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>🛡️ Giriş Kayıtları</h2>

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Kullanıcı</th>
            <th>IP Adresi</th>
            <th>Tarayıcı</th>
            <th>Giriş Zamanı</th>
        </tr>
        <?php foreach ($loglar as $log): ?>
            <tr>
                <td><?= $log['kullanici_adi'] ?></td>
                <td><?= $log['ip_adresi'] ?></td>
                <td style="font-size: 12px;"><?= substr($log['tarayici'], 0, 100) ?>...</td>
                <td><?= $log['giris_zamani'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
