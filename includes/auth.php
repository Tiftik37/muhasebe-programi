<?php
session_start();
if (!isset($_SESSION['kullanici_adi'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
