<?php
include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// Sadece admin erişsin
if ($_SESSION['rol'] !== 'admin') {
    echo "<div style='margin-left:240px; padding:20px; color:red;'>Bu sayfaya sadece admin erişebilir!</div>";
    exit;
}

// Yeni kullanıcı ekle
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ekle'])) {
    $ad_soyad = $_POST['ad_soyad'];
    $kullanici = $_POST['kullanici'];
    $sifre = password_hash($_POST['sifre'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $ekle = $baglanti->prepare("INSERT INTO kullanicilar (ad_soyad, kullanici_adi, sifre, rol) VALUES (?, ?, ?, ?)");
    $ekle->execute([$ad_soyad, $kullanici, $sifre, $rol]);
    header("Location: kullanicilar.php");
}

// Sil
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    if ($_SESSION['rol'] === 'admin') {
        $sil = $baglanti->prepare("DELETE FROM kullanicilar WHERE id = ?");
        $sil->execute([$id]);
        header("Location: kullanicilar.php");
    }
}

// Listele
$veriler = $baglanti->query("SELECT * FROM kullanicilar ORDER BY kayit_tarihi DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="margin-left: 240px; padding: 20px;">
    <h2>👤 Kullanıcı Yönetimi</h2>

    <!-- FORM -->
    <form method="POST" style="background: #f0f0f0; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
        <h3>Yeni Kullanıcı Ekle</h3>
        <input type="text" name="ad_soyad" placeholder="Ad Soyad" required>
        <input type="text" name="kullanici" placeholder="Kullanıcı Adı" required>
        <input type="password" name="sifre" placeholder="Şifre" required>
        <select name="rol">
            <option value="kullanici">Kullanıcı</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit" name="ekle">Ekle</button>
    </form>

    <!-- TABLO -->
    <h3>Kullanıcılar</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; background: #fff;">
        <tr>
            <th>Ad Soyad</th>
            <th>Kullanıcı Adı</th>
            <th>Rol</th>
            <th>Kayıt Tarihi</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($veriler as $k): ?>
            <tr>
                <td><?= $k['ad_soyad'] ?></td>
                <td><?= $k['kullanici_adi'] ?></td>
                <td><?= strtoupper($k['rol']) ?></td>
                <td><?= $k['kayit_tarihi'] ?></td>
                <td>
                    <?php if ($_SESSION['kullanici_adi'] !== $k['kullanici_adi']): ?>
                        <a href="?sil=<?= $k['id'] ?>" onclick="return confirm('Silinsin mi?')">🗑️</a>
                    <?php else: ?>
                        <span>🛡️</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
