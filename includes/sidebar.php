<div style="position: fixed; top: 0; left: 0; width: 220px; height: 100vh; overflow-y: auto; background-color: #1e1e2f; color: white; padding-top: 20px; z-index: 1000;">
    <h2 style="text-align: center; margin-bottom: 20px;">📘 Panel</h2>
    <ul style="list-style: none; padding-left: 0; margin: 0;">
        <li><a href="dashboard.php" class="menu-item">📊 Dashboard</a></li>
        <li><a href="gelir_gider.php" class="menu-item">📥 Gelir-Gider</a></li>

        <li class="accordion">
            <div class="menu-item accordion-toggle">💰 Kasa / Banka</div>
            <ul class="submenu">
                <li><a href="kasalar.php" class="menu-item">💵 Kasalar</a></li>
                <li><a href="bankalar.php" class="menu-item">🏦 Bankalar</a></li>

            </ul>
        </li>

        <li class="accordion">
            <div class="menu-item accordion-toggle">👤 Personel</div>
            <ul class="submenu">
                <li><a href="personeller.php" class="menu-item">👤 Personeller</a></li>
                <li><a href="personel_maas.php" class="menu-item">💰 Personel Maaş</a></li>
                <li><a href="personel_giris.php" class="menu-item">⏱️ Personel Giriş</a></li>
                <li><a href="personel_rapor.php" class="menu-item">📄 Personel Raporu</a></li>
            </ul>
        </li>

        <li class="accordion">
            <div class="menu-item accordion-toggle">📦 Stok / İşlem</div>
            <ul class="submenu">
                <li><a href="stoklar.php" class="menu-item">📦 Stoklar</a></li>
                <li><a href="alislar.php" class="menu-item">🛒 Alışlar</a></li>
                <li><a href="satislar.php" class="menu-item">🧾 Satışlar</a></li>
            </ul>
        </li>

        <li><a href="cariler.php" class="menu-item">📁 Cariler</a></li>
        <li><a href="cari_hareketler.php" class="menu-item">🔄 Cari Hareketler</a></li>
        <li><a href="faturalar.php" class="menu-item">🧾 Faturalar</a></li>
        <li><a href="borclar.php" class="menu-item">📉 Borçlar</a></li>
        <li><a href="butce.php" class="menu-item">📋 Bütçe</a></li>
        <li><a href="tasarruflar.php" class="menu-item">💼 Tasarruflar</a></li>
        <li><a href="yatirimlar.php" class="menu-item">📈 Yatırımlar</a></li>

        <li class="accordion">
            <div class="menu-item accordion-toggle">⚙️ Sistem</div>
            <ul class="submenu">
                <li><a href="ayarlar.php" class="menu-item">⚙️ Ayarlar</a></li>
                <li><a href="kullanicilar.php" class="menu-item">🧑‍💼 Kullanıcılar</a></li>
                <li><a href="giris_kayitlari.php" class="menu-item">🕵️‍♂️ Giriş Kayıtları</a></li>
            </ul>
        </li>

        <li><a href="logout.php" class="menu-item">🚪 Çıkış Yap</a></li>
    </ul>
</div>

<style>
.menu-item {
    display: block;
    padding: 10px 20px;
    color: white;
    text-decoration: none;
    transition: background 0.2s;
}
.menu-item:hover {
    background-color: #2a2a3d;
}
.accordion .submenu {
    display: none;
    padding-left: 10px;
}
.accordion.open .submenu {
    display: block;
}
</style>

<script>
// Basit JS toggle sistemi
document.querySelectorAll('.accordion-toggle').forEach(item => {
    item.addEventListener('click', () => {
        const parent = item.parentElement;
        parent.classList.toggle('open');
    });
});
</script>
