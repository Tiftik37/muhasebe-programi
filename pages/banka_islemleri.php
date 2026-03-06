<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// Bankaları çek
$bankalar = $baglanti->query("SELECT * FROM bankalar ORDER BY ad ASC")->fetchAll(PDO::FETCH_ASSOC);

// EKLE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $banka_id = $_POST['banka_id'];
    $tutar = $_POST['tutar'];
    $tarih = $_POST['tarih'];
    $islem_tipi = $_POST['islem_tipi']; // Giriş veya Çıkış
    $aciklama = $_POST['aciklama'];

    // Veritabanına ekle
    $ekle = $baglanti->prepare("INSERT INTO banka_islemleri (banka_id, tutar, tarih, islem_tipi, aciklama) VALUES (?, ?, ?, ?, ?)");
    $ekle->execute([$banka_id, $tutar, $tarih, $islem_tipi, $aciklama]);

    // Banka bakiyesini güncelle
    if ($islem_tipi == 'Giriş') {
        $baglanti->prepare("UPDATE bankalar SET bakiye = bakiye + ? WHERE id = ?")->execute([$tutar, $banka_id]);
    } else {
        $baglanti->prepare("UPDATE bankalar SET bakiye = bakiye - ? WHERE id = ?")->execute([$tutar, $banka_id]);
    }

    header("Location: banka_islemleri.php");
}

// Listele
$islemler = $baglanti->query("
    SELECT i.*, b.ad AS banka_adi 
    FROM banka_islemleri i 
    LEFT JOIN bankalar b ON i.banka_id = b.id 
    ORDER BY i.tarih DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left:240px; padding:20px;">
    <h2>🏦 Banka İşlemleri</h2>

    <form method="POST" style="background:#f9f9f9; padding:20px; border-radius:10px;">
        <h3>Yeni Banka İşlemi</h3>
        <select name="banka_id" required>
            <option disabled selected>Banka Seç</option>
            <?php foreach ($bankalar as $b): ?>
                <option value="<?= $b['id'] ?>"><?= $b['ad'] ?> (<?= number_format($b['bakiye'],2) ?> ₺)</option>
            <?php endforeach; ?>
        </select>

        <select name="islem_tipi" required>
            <option disabled selected>İşlem Tipi</option>
            <option value="Giriş">Giriş (Para Girdi)</option>
            <option value="Çıkış">Çıkış (Para Çıktı)</option>
        </select>

        <input type="number" step="0.01" name="tutar" placeholder="Tutar (₺)" required>
        <input type="date" name="tarih" required>
        <textarea name="aciklama" placeholder="Açıklama (İsteğe Bağlı)"></textarea>
        <button type="submit" name="ekle">İşlemi Kaydet</button>
    </form>

    <h3 style="margin-top:40px;">📄 Banka İşlem Geçmişi</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Banka</th>
            <th>İşlem Tipi</th>
            <th>Tutar</th>
            <th>Tarih</th>
            <th>Açıklama</th>
        </tr>
        <?php foreach ($islemler as $i): ?>
            <tr>
                <td><?= $i['banka_adi'] ?></td>
                <td><?= $i['islem_tipi'] ?></td>
                <td><?= number_format($i['tutar'],2) ?> ₺</td>
                <td><?= $i['tarih'] ?></td>
                <td><?= $i['aciklama'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
