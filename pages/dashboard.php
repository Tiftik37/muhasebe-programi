<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../includes/auth.php';
include '../includes/db.php';
include '../includes/header.php';
include '../includes/sidebar.php';

// Fonksiyon: Güvenli sayı döndür
function getSafeSum($query, $baglanti) {
    $result = $baglanti->query($query)->fetchColumn();
    return is_numeric($result) ? $result : 0;
}

// Özet Veriler
$toplam_kasa = getSafeSum("SELECT SUM(bakiye) FROM kasalar", $baglanti);
$toplam_banka = getSafeSum("SELECT SUM(bakiye) FROM bankalar", $baglanti);
$bugun = date('Y-m-d');
$bugunku_gelir = getSafeSum("SELECT SUM(tutar) FROM gelir_gider WHERE tur = 'Gelir' AND DATE(tarih) = '$bugun'", $baglanti);
$bugunku_gider = getSafeSum("SELECT SUM(tutar) FROM gelir_gider WHERE tur = 'Gider' AND DATE(tarih) = '$bugun'", $baglanti);

// Son 7 Günlük Grafik Verisi
$gelirler = [];
$giderler = [];
$tarihler = [];

for ($i = 6; $i >= 0; $i--) {
    $tarih = date('Y-m-d', strtotime("-$i days"));
    $tarihler[] = $tarih;
    $gelirler[] = getSafeSum("SELECT SUM(tutar) FROM gelir_gider WHERE tur = 'Gelir' AND DATE(tarih) = '$tarih'", $baglanti);
    $giderler[] = getSafeSum("SELECT SUM(tutar) FROM gelir_gider WHERE tur = 'Gider' AND DATE(tarih) = '$tarih'", $baglanti);
}
?>

<div class="content">
    <h2 class="page-title">📊 Dashboard</h2>

    <div class="dashboard-cards">
        <div class="card green">
            <h3>💵 Toplam Kasa</h3>
            <p><?= number_format($toplam_kasa, 2) ?> ₺</p>
        </div>
        <div class="card blue">
            <h3>🏦 Toplam Banka</h3>
            <p><?= number_format($toplam_banka, 2) ?> ₺</p>
        </div>
        <div class="card yellow">
            <h3>📥 Bugünkü Gelir</h3>
            <p><?= number_format($bugunku_gelir, 2) ?> ₺</p>
        </div>
        <div class="card red">
            <h3>📤 Bugünkü Gider</h3>
            <p><?= number_format($bugunku_gider, 2) ?> ₺</p>
        </div>
    </div>

    <div class="chart-wrapper">
        <canvas id="gelirGiderChart" height="120"></canvas>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('gelirGiderChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($tarihler) ?>,
        datasets: [
            {
                label: 'Gelir (₺)',
                data: <?= json_encode($gelirler) ?>,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40,167,69,0.1)',
                fill: true,
                tension: 0.3
            },
            {
                label: 'Gider (₺)',
                data: <?= json_encode($giderler) ?>,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220,53,69,0.1)',
                fill: true,
                tension: 0.3
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            legend: { position: 'top' }
        }
    }
});
</script>
