<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// Cariler (sadece alıcılar)
$cariler = $baglanti->query("SELECT * FROM cariler WHERE tip='Alıcı'")->fetchAll(PDO::FETCH_ASSOC);

// Stoklar
$stoklar = $baglanti->query("SELECT * FROM stoklar")->fetchAll(PDO::FETCH_ASSOC);

// Ekleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $cari_id = $_POST['cari_id'];
    $stok_id = $_POST['stok_id'];
    $miktar = $_POST['miktar'];
    $birim_fiyat = $_POST['birim_fiyat'];
    $toplam_tutar = $miktar * $birim_fiyat;
    $islem_tarihi = $_POST['islem_tarihi'];
    $aciklama = $_POST['aciklama'];

    // Satış kaydı
    $ekle = $baglanti->prepare("INSERT INTO satislar (cari_id, stok_id, miktar, birim_fiyat, toplam_tutar, islem_tarihi, aciklama) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $ekle->execute([$cari_id, $stok_id, $miktar, $birim_fiyat, $toplam_tutar, $islem_tarihi, $aciklama]);

    // Stok azalt
    $guncelle = $baglanti->prepare("UPDATE stoklar SET miktar = miktar - ? WHERE id = ?");
    $guncelle->execute([$miktar, $stok_id]);

    // Cari harekete ekleme
    $hareket = $baglanti->prepare("INSERT INTO cari_hareketler (cari_id, islem_tipi, tutar, aciklama, islem_tarihi) VALUES (?, 'Alacak', ?, ?, ?)");
    $hareket->execute([$cari_id, $toplam_tutar, $aciklama, $islem_tarihi]);

    header("Location: satislar.php");
}

// Listeleme
$liste = $baglanti->query("
    SELECT s.*, c.unvan, st.stok_adi 
    FROM satislar s 
    JOIN cariler c ON s.cari_id = c.id 
    JOIN stoklar st ON s.stok_id = st.id 
    ORDER BY s.islem_tarihi DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>💰 Satış İşlemleri</h2>

    <form method="POST" style="background: #f8f8f8; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
        <h3>Yeni Satış</h3>
        <select name="cari_id" required>
            <option value="">Alıcı Seç</option>
            <?php foreach ($cariler as $c): ?>
                <option value="<?= $c['id'] ?>"><?= $c['unvan'] ?></option>
            <?php endforeach; ?>
        </select>
        <select name="stok_id" required>
            <option value="">Stok Seç</option>
            <?php foreach ($stoklar as $s): ?>
                <option value="<?= $s['id'] ?>"><?= $s['stok_adi'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" step="0.01" name="miktar" placeholder="Miktar" required>
        <input type="number" step="0.01" name="birim_fiyat" placeholder="Birim Fiyat" required>
        <input type="date" name="islem_tarihi" required>
        <textarea name="aciklama" placeholder="Açıklama"></textarea>
        <button type="submit" name="ekle">Kaydet</button>
    </form>

    <h3>Satış Listesi</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Alıcı</th>
            <th>Stok</th>
            <th>Miktar</th>
            <th>Birim Fiyat</th>
            <th>Toplam</th>
            <th>Tarih</th>
            <th>Açıklama</th>
        </tr>
        <?php foreach ($liste as $s): ?>
            <tr>
                <td><?= $s['unvan'] ?></td>
                <td><?= $s['stok_adi'] ?></td>
                <td><?= $s['miktar'] ?></td>
                <td><?= number_format($s['birim_fiyat'], 2) ?> ₺</td>
                <td><?= number_format($s['toplam_tutar'], 2) ?> ₺</td>
                <td><?= $s['islem_tarihi'] ?></td>
                <td><?= $s['aciklama'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
