<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// Kasa listeleme
$kasa_liste = $baglanti->query("SELECT * FROM kasa ORDER BY tarih DESC")->fetchAll(PDO::FETCH_ASSOC);

// Cari liste
$cariler = $baglanti->query("SELECT * FROM cariler")->fetchAll(PDO::FETCH_ASSOC);

// Ekleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $cari_id = $_POST['cari_id'];
    $tutar = $_POST['tutar'];
    $islem_tipi = $_POST['islem_tipi'];
    $aciklama = $_POST['aciklama'];
    $tarih = $_POST['tarih'];

    // Kasa işlemi
    $kasa_ekle = $baglanti->prepare("INSERT INTO kasa (cari_id, tutar, islem_tipi, aciklama, tarih) VALUES (?, ?, ?, ?, ?)");
    $kasa_ekle->execute([$cari_id, $tutar, $islem_tipi, $aciklama, $tarih]);

    // Cari harekete yaz
    $tip = $islem_tipi == 'Giris' ? 'Alacak' : 'Borç';
    $hareket = $baglanti->prepare("INSERT INTO cari_hareketler (cari_id, islem_tipi, tutar, aciklama, islem_tarihi) VALUES (?, ?, ?, ?, ?)");
    $hareket->execute([$cari_id, $tip, $tutar, $aciklama, $tarih]);

    header("Location: kasa.php");
}
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>💵 Kasa İşlemleri</h2>

    <form method="POST" style="background:#f8f8f8; padding:20px; border-radius:10px; margin-bottom:30px;">
        <h3>Yeni Kasa İşlemi</h3>
        <select name="cari_id" required>
            <option value="">Cari Seç</option>
            <?php foreach ($cariler as $c): ?>
                <option value="<?= $c['id'] ?>"><?= $c['unvan'] ?></option>
            <?php endforeach; ?>
        </select>
        <select name="islem_tipi" required>
            <option value="">İşlem Tipi</option>
            <option value="Giris">Giriş</option>
            <option value="Cikis">Çıkış</option>
        </select>
        <input type="number" step="0.01" name="tutar" placeholder="Tutar" required>
        <input type="date" name="tarih" required>
        <textarea name="aciklama" placeholder="Açıklama"></textarea>
        <button type="submit" name="ekle">Kaydet</button>
    </form>

    <h3>Kasa Hareketleri</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Cari</th>
            <th>Tip</th>
            <th>Tutar</th>
            <th>Açıklama</th>
            <th>Tarih</th>
        </tr>
        <?php foreach ($kasa_liste as $k): ?>
            <tr>
                <td><?= $k['cari_id'] ?></td>
                <td style="color:<?= $k['islem_tipi'] == 'Giris' ? 'green' : 'red' ?>; font-weight: bold;">
                    <?= $k['islem_tipi'] ?>
                </td>
                <td><?= number_format($k['tutar'], 2) ?> ₺</td>
                <td><?= $k['aciklama'] ?></td>
                <td><?= $k['tarih'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
