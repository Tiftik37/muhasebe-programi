<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// EKLEME
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $stok_id = $_POST['stok_id'];
    $cari_id = $_POST['cari_id'];
    $miktar = $_POST['miktar'];
    $birim_fiyat = $_POST['birim_fiyat'];
    $kasa_id = $_POST['kasa_id'];
    $aciklama = $_POST['aciklama'];
    $tarih = date('Y-m-d');

    $toplam_tutar = $miktar * $birim_fiyat;

    // Stoğu artır
    $baglanti->prepare("UPDATE stoklar SET miktar = miktar + ? WHERE id = ?")->execute([$miktar, $stok_id]);

    // Cari borç hareketi
    $baglanti->prepare("INSERT INTO cari_hareketler (cari_id, tutar, aciklama, tip, tarih) VALUES (?, ?, ?, 'Borç', ?)")
        ->execute([$cari_id, $toplam_tutar, $aciklama, $tarih]);

    // Kasa hareketi (Çıkış)
    $baglanti->prepare("INSERT INTO kasa_hareketler (kasa_id, tutar, aciklama, tip, tarih) VALUES (?, ?, ?, 'Çıkış', ?)")
        ->execute([$kasa_id, $toplam_tutar, $aciklama, $tarih]);

    // Alış kaydı
    $baglanti->prepare("INSERT INTO alislar (stok_id, cari_id, miktar, birim_fiyat, toplam_tutar, kasa_id, aciklama, tarih)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
        ->execute([$stok_id, $cari_id, $miktar, $birim_fiyat, $toplam_tutar, $kasa_id, $aciklama, $tarih]);

    header("Location: alislar.php");
}

// LİSTE
$stoklar = $baglanti->query("SELECT * FROM stoklar")->fetchAll(PDO::FETCH_ASSOC);
$cariler = $baglanti->query("SELECT * FROM cariler WHERE tip='Satıcı'")->fetchAll(PDO::FETCH_ASSOC);
$kasalar = $baglanti->query("SELECT * FROM kasalar")->fetchAll(PDO::FETCH_ASSOC);

$alislar = $baglanti->query("SELECT a.*, s.stok_adi, c.unvan AS cari_adi, k.ad AS kasa_adi 
                             FROM alislar a
                             JOIN stoklar s ON a.stok_id = s.id
                             JOIN cariler c ON a.cari_id = c.id
                             JOIN kasalar k ON a.kasa_id = k.id
                             ORDER BY a.id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left:240px; padding:20px;">
    <h2>📥 Alış İşlemleri</h2>

    <form method="POST" style="background:#f5f5f5; padding:20px; border-radius:10px; margin-bottom:30px;">
        <h3>Yeni Alış</h3>
        <select name="stok_id" required>
            <option value="">Stok Seç</option>
            <?php foreach ($stoklar as $s): ?>
                <option value="<?= $s['id'] ?>"><?= $s['stok_adi'] ?></option>
            <?php endforeach; ?>
        </select>
        <select name="cari_id" required>
            <option value="">Satıcı Seç</option>
            <?php foreach ($cariler as $c): ?>
                <option value="<?= $c['id'] ?>"><?= $c['unvan'] ?></option>
            <?php endforeach; ?>
        </select>
        <select name="kasa_id" required>
            <option value="">Kasa Seç</option>
            <?php foreach ($kasalar as $k): ?>
                <option value="<?= $k['id'] ?>"><?= $k['ad'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="miktar" placeholder="Miktar" step="0.01" required>
        <input type="number" name="birim_fiyat" placeholder="Birim Fiyat" step="0.01" required>
        <textarea name="aciklama" placeholder="Açıklama"></textarea>
        <button type="submit" name="ekle">Kaydet</button>
    </form>

    <h3>Geçmiş Alışlar</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Stok</th>
            <th>Satıcı</th>
            <th>Miktar</th>
            <th>Birim Fiyat</th>
            <th>Toplam</th>
            <th>Kasa</th>
            <th>Açıklama</th>
            <th>Tarih</th>
        </tr>
        <?php foreach ($alislar as $a): ?>
            <tr>
                <td><?= $a['stok_adi'] ?></td>
                <td><?= $a['cari_adi'] ?></td>
                <td><?= $a['miktar'] ?></td>
                <td><?= number_format($a['birim_fiyat'], 2) ?> ₺</td>
                <td><?= number_format($a['toplam_tutar'], 2) ?> ₺</td>
                <td><?= $a['kasa_adi'] ?></td>
                <td><?= $a['aciklama'] ?></td>
                <td><?= $a['tarih'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
