<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// Kasa listesi
$kasalar = $baglanti->query("SELECT * FROM kasalar ORDER BY ad ASC")->fetchAll(PDO::FETCH_ASSOC);

// Manuel işlem ekleme
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $kasa_id = $_POST['kasa_id'];
    $tutar = floatval($_POST['tutar']);
    $tur = $_POST['tur']; // Giriş / Çıkış
    $tarih = $_POST['tarih'];
    $aciklama = $_POST['aciklama'];

    // Bakiye güncelle
    if ($tur == "Giriş") {
        $baglanti->prepare("UPDATE kasalar SET bakiye = bakiye + ? WHERE id = ?")->execute([$tutar, $kasa_id]);
    } else {
        $baglanti->prepare("UPDATE kasalar SET bakiye = bakiye - ? WHERE id = ?")->execute([$tutar, $kasa_id]);
    }

    // Kayıt ekle
    $baglanti->prepare("INSERT INTO kasa_hareketleri (kasa_id, tutar, tur, tarih, aciklama) VALUES (?, ?, ?, ?, ?)")
        ->execute([$kasa_id, $tutar, $tur, $tarih, $aciklama]);

    header("Location: kasa_islemleri.php");
    exit;
}

// Hareketler
$hareketler = $baglanti->query("
    SELECT h.*, k.ad AS kasa_adi 
    FROM kasa_hareketleri h 
    LEFT JOIN kasalar k ON h.kasa_id = k.id 
    ORDER BY h.tarih DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left:240px; padding:20px;">
    <h2>💼 Kasa İşlemleri</h2>

    <form method="POST" style="background:#f9f9f9; padding:20px; border-radius:10px;">
        <h3>Manuel Kasa İşlemi</h3>
        <select name="kasa_id" required>
            <option disabled selected>Kasa Seç</option>
            <?php foreach ($kasalar as $k): ?>
                <option value="<?= $k['id'] ?>"><?= $k['ad'] ?> (<?= number_format($k['bakiye'], 2) ?> ₺)</option>
            <?php endforeach; ?>
        </select>

        <input type="number" step="0.01" name="tutar" placeholder="Tutar" required>
        <select name="tur" required>
            <option value="Giriş">Giriş (+)</option>
            <option value="Çıkış">Çıkış (-)</option>
        </select>
        <input type="date" name="tarih" required>
        <textarea name="aciklama" placeholder="Açıklama"></textarea>
        <button type="submit" name="ekle">Kaydet</button>
    </form>

    <h3 style="margin-top:40px;">📋 Kasa Hareketleri</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:white;">
        <tr>
            <th>Kasa</th>
            <th>Tutar</th>
            <th>Tür</th>
            <th>Tarih</th>
            <th>Açıklama</th>
        </tr>
        <?php foreach ($hareketler as $h): ?>
            <tr>
                <td><?= $h['kasa_adi'] ?></td>
                <td><?= number_format($h['tutar'], 2) ?> ₺</td>
                <td><?= $h['tur'] ?></td>
                <td><?= $h['tarih'] ?></td>
                <td><?= htmlspecialchars($h['aciklama']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
