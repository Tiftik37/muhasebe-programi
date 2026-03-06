<?php
session_start();
if (isset($_SESSION['kullanici_adi'])) {
    header("Location: pages/dashboard.php");
} else {
    header("Location: auth/login.php");
}
exit;
