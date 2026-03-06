<!-- includes/header.php -->
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Strotik Muhasebe Sistemi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .header {
            position: fixed;
            top: 0;
            left: 220px;
            right: 0;
            height: 60px;
            background: #2e2e3e;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 999;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .header a {
            color: #ffcbcb;
            text-decoration: none;
            margin-left: 10px;
        }

        body {
            padding-top: 60px;
        }
    </style>
</head>
<body>

<div class="header">
    <div style="font-size: 18px; font-weight: bold;">📊 Strotik Muhasebe</div>
    <div style="font-size: 14px;">
        Hoş geldin, <strong><?= $_SESSION['kullanici_adi'] ?? 'Misafir' ?></strong>
        | <a href="/auth/logout.php">Çıkış</a>
    </div>
</div>
