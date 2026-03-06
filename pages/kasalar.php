<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// Kasa ID (örnek: id=1)
$kasa_id = 1;

// EKLEME
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $tarih = $_POST['islem_tarihi'];
    $tip = $_POST['tur']; // Giriş / Çıkış
    $tutar = floatval($_POST['miktar']);
    $aciklama = $_POST['aciklama'];

    // İşlem kaydet
    $baglanti->prepare("INSERT INTO kasa_hareketler (kasa_id, tutar, tip, aciklama, tarih) VALUES (?, ?, ?, ?, ?)")
             ->execute([$kasa_id, $tutar, $tip, $aciklama, $tarih]);

    // Bakiye güncelle
    if ($tip == "Giriş") {
        $baglanti->prepare("UPDATE kasalar SET bakiye = bakiye + ? WHERE id = ?")->execute([$tutar, $kasa_id]);
    } else {
        $baglanti->prepare("UPDATE kasalar SET bakiye = bakiye - ? WHERE id = ?")->execute([$tutar, $kasa_id]);
    }

    header("Location: kasalar.php");
    exit;
}

// SİLME
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    $hareket = $baglanti->prepare("SELECT * FROM kasa_hareketler WHERE id = ?");
    $hareket->execute([$id]);
    $veri = $hareket->fetch(PDO::FETCH_ASSOC);

    if ($veri) {
        // Bakiye düzelt
        if ($veri['tip'] == "Giriş") {
            $baglanti->prepare("UPDATE kasalar SET bakiye = bakiye - ? WHERE id = ?")->execute([$veri['tutar'], $veri['kasa_id']]);
        } else {
            $baglanti->prepare("UPDATE kasalar SET bakiye = bakiye + ? WHERE id = ?")->execute([$veri['tutar'], $veri['kasa_id']]);
        }

        // Sil
        $baglanti->prepare("DELETE FROM kasa_hareketler WHERE id = ?")->execute([$id]);
    }

    header("Location: kasalar.php");
    exit;
}

// Verileri çek
$hareketler = $baglanti->query("SELECT * FROM kasa_hareketler WHERE kasa_id = $kasa_id ORDER BY tarih DESC")->fetchAll(PDO::FETCH_ASSOC);
$kasa_bilgi = $baglanti->query("SELECT * FROM kasalar WHERE id = $kasa_id")->fetch(PDO::FETCH_ASSOC);
$bakiye = $kasa_bilgi['bakiye'] ?? 0;
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>🏦 Kasa İşlemleri</h2>

    <div style="background: #e8f5e9; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <strong>📌 Güncel Kasa Bakiyesi:</strong> <span style="font-size: 20px;"><?= number_format($bakiye, 2) ?> ₺</span><br>
        <strong>📝 Açıklama:</strong> <?= htmlspecialchars($kasa_bilgi['aciklama'] ?? 'Yok') ?><br>
        <strong>📅 Oluşturulma Tarihi:</strong> <?= $kasa_bilgi['olusturma_tarihi'] ?? 'Yok' ?>
    </div>

    <form method="POST" style="background: #f4f4f4; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
        <h3>Yeni Kasa İşlemi</h3>
        <input type="date" name="islem_tarihi" required>
        <select name="tur">
            <option value="Giriş">Giriş</option>
            <option value="Çıkış">Çıkış</option>
        </select>
        <input type="number" step="0.01" name="miktar" placeholder="Tutar ₺" required>
        <textarea name="aciklama" placeholder="Açıklama"></textarea>
        <button type="submit" name="ekle">Ekle</button>
    </form>

    <h3>Kasa Hareketleri</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Tarih</th>
            <th>Tür</th>
            <th>Miktar</th>
            <th>Açıklama</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($hareketler as $k): ?>
            <tr>
                <td><?= $k['tarih'] ?></td>
                <td><?= $k['tip'] ?></td>
                <td><?= number_format($k['tutar'], 2) ?> ₺</td>
                <td><?= htmlspecialchars($k['aciklama']) ?></td>
                <td><a href="?sil=<?= $k['id'] ?>" onclick="return confirm('Silinsin mi?')">🗑️</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>


