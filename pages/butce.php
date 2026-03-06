<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// Ekleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $kategori = $_POST['kategori'];
    $limit = floatval($_POST['butce_limiti']);
    $baslangic = $_POST['baslangic_tarihi'];

    $baglanti->prepare("INSERT INTO butce (kategori, butce_limiti, baslangic_tarihi) VALUES (?, ?, ?)")
             ->execute([$kategori, $limit, $baslangic]);

    header("Location: butce.php");
}

// Listeleme
$butceler = $baglanti->query("SELECT * FROM butce ORDER BY baslangic_tarihi DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left:240px; padding:20px;">
    <h2>📊 Bütçe Planlaması</h2>

    <form method="POST" style="background:#f0f0f0; padding:20px; border-radius:10px;">
        <h3>Yeni Bütçe Girişi</h3>
        <input type="text" name="kategori" placeholder="Kategori (Yemek, Ulaşım, Fatura...)" required>
        <input type="number" step="0.01" name="butce_limiti" placeholder="Bütçe Limiti ₺" required>
        <input type="date" name="baslangic_tarihi" required>
        <button type="submit" name="ekle">Kaydet</button>
    </form>

    <h3 style="margin-top:30px;">📋 Kayıtlı Bütçeler</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background:#fff;">
        <tr>
            <th>Kategori</th>
            <th>Limit</th>
            <th>Başlangıç Tarihi</th>
        </tr>
        <?php foreach ($butceler as $b): ?>
            <tr>
                <td><?= htmlspecialchars($b['kategori']) ?></td>
                <td><?= number_format($b['butce_limiti'],2) ?> ₺</td>
                <td><?= $b['baslangic_tarihi'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
