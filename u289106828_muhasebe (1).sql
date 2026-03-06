-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 10 Haz 2025, 12:45:00
-- Sunucu sürümü: 10.11.10-MariaDB
-- PHP Sürümü: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `u289106828_muhasebe`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `alislar`
--

CREATE TABLE `alislar` (
  `id` int(11) NOT NULL,
  `stok_id` int(11) DEFAULT NULL,
  `cari_id` int(11) DEFAULT NULL,
  `miktar` float DEFAULT NULL,
  `birim_fiyat` float DEFAULT NULL,
  `toplam_tutar` float DEFAULT NULL,
  `kasa_id` int(11) DEFAULT NULL,
  `aciklama` text DEFAULT NULL,
  `tarih` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ayarlar`
--

CREATE TABLE `ayarlar` (
  `id` int(11) NOT NULL,
  `site_basligi` varchar(255) DEFAULT NULL,
  `site_aciklama` text DEFAULT NULL,
  `eposta` varchar(100) DEFAULT NULL,
  `telefon` varchar(50) DEFAULT NULL,
  `adres` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `banka`
--

CREATE TABLE `banka` (
  `id` int(11) NOT NULL,
  `cari_id` int(11) DEFAULT NULL,
  `tutar` float DEFAULT NULL,
  `islem_tipi` varchar(10) DEFAULT NULL,
  `aciklama` text DEFAULT NULL,
  `tarih` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bankalar`
--

CREATE TABLE `bankalar` (
  `id` int(11) NOT NULL,
  `banka_adi` varchar(100) DEFAULT NULL,
  `iban` varchar(100) DEFAULT NULL,
  `hesap_sahibi` varchar(100) DEFAULT NULL,
  `bakiye` float DEFAULT NULL,
  `ad` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `bankalar`
--

INSERT INTO `bankalar` (`id`, `banka_adi`, `iban`, `hesap_sahibi`, `bakiye`, `ad`) VALUES
(1, 'garanti', 'tr534543543543', 'şehriban tiftik', 295000, NULL),
(2, 'garantia', 'TR130006701000000030703856', 'şehriban tiftika', 300000, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `banka_hareketler`
--

CREATE TABLE `banka_hareketler` (
  `id` int(11) NOT NULL,
  `banka_id` int(11) NOT NULL,
  `tutar` decimal(10,2) NOT NULL,
  `tip` varchar(10) NOT NULL,
  `aciklama` varchar(255) DEFAULT NULL,
  `tarih` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `banka_islemleri`
--

CREATE TABLE `banka_islemleri` (
  `id` int(11) NOT NULL,
  `banka_id` int(11) DEFAULT NULL,
  `tutar` float DEFAULT NULL,
  `tarih` date DEFAULT NULL,
  `islem_tipi` varchar(10) DEFAULT NULL,
  `aciklama` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `borclar`
--

CREATE TABLE `borclar` (
  `id` int(11) NOT NULL,
  `borc_turu` varchar(100) DEFAULT NULL,
  `tutar` float DEFAULT NULL,
  `vade_tarihi` date DEFAULT NULL,
  `odeme_yolu` varchar(20) DEFAULT NULL,
  `odeme_id` int(11) DEFAULT NULL,
  `durum` varchar(20) DEFAULT NULL,
  `aciklama` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `butce`
--

CREATE TABLE `butce` (
  `id` int(11) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `butce_limiti` float DEFAULT NULL,
  `baslangic_tarihi` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cariler`
--

CREATE TABLE `cariler` (
  `id` int(11) NOT NULL,
  `unvan` varchar(255) DEFAULT NULL,
  `cari_tipi` varchar(20) DEFAULT NULL,
  `telefon` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `adres` text DEFAULT NULL,
  `vergi_no` varchar(50) DEFAULT NULL,
  `vergi_dairesi` varchar(100) DEFAULT NULL,
  `tip` varchar(20) DEFAULT 'Alıcı'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `cariler`
--

INSERT INTO `cariler` (`id`, `unvan`, `cari_tipi`, `telefon`, `email`, `adres`, `vergi_no`, `vergi_dairesi`, `tip`) VALUES
(1, 'şerefsiz lider', 'Alıcı', '05360603666', 'km.tftk@gmail.com', 'örnek mahallesi mihrace sokak, no: 53 daire: 8', 'fsdafef4f4f', 'fsdafdsaf', 'Alıcı'),
(2, 'şerefsiz lider2', 'Satıcı', '05360603666', 'km.tftk@gmail.com', 'örnek mahallesi mihrace sokak, no: 53 daire: 8', 'fsdafef4f4f', 'fsdafdsaf', 'Alıcı');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cari_hareketler`
--

CREATE TABLE `cari_hareketler` (
  `id` int(11) NOT NULL,
  `cari_id` int(11) DEFAULT NULL,
  `islem_tipi` varchar(20) DEFAULT NULL,
  `tutar` float DEFAULT NULL,
  `aciklama` text DEFAULT NULL,
  `islem_tarihi` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `cari_hareketler`
--

INSERT INTO `cari_hareketler` (`id`, `cari_id`, `islem_tipi`, `tutar`, `aciklama`, `islem_tarihi`) VALUES
(1, 1, 'Alacak', 50000000, '', '2002-02-08');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `faturalar`
--

CREATE TABLE `faturalar` (
  `id` int(11) NOT NULL,
  `fatura_turu` varchar(100) DEFAULT NULL,
  `tutar` float DEFAULT NULL,
  `vade_tarihi` date DEFAULT NULL,
  `odeme_yolu` varchar(20) DEFAULT NULL,
  `odeme_id` int(11) DEFAULT NULL,
  `durum` varchar(20) DEFAULT NULL,
  `aciklama` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `gelirler`
--

CREATE TABLE `gelirler` (
  `id` int(11) NOT NULL,
  `tutar` decimal(10,2) NOT NULL,
  `aciklama` text DEFAULT NULL,
  `tarih` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `gelir_gider`
--

CREATE TABLE `gelir_gider` (
  `id` int(11) NOT NULL,
  `tur` varchar(20) DEFAULT NULL,
  `odeme_yolu` varchar(20) DEFAULT NULL,
  `odeme_id` int(11) DEFAULT NULL,
  `tutar` float DEFAULT NULL,
  `tarih` date DEFAULT NULL,
  `aciklama` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `gelir_gider`
--

INSERT INTO `gelir_gider` (`id`, `tur`, `odeme_yolu`, `odeme_id`, `tutar`, `tarih`, `aciklama`) VALUES
(1, 'Gelir', 'Kasa', 1, 5000, '2025-02-08', ''),
(2, 'Gider', 'Kasa', 1, 5000, '2025-03-08', ''),
(3, 'Gider', 'Kasa', 1, 5000, '2025-02-02', ''),
(4, 'Gelir', 'Kasa', NULL, 5000, '2025-02-08', ''),
(5, 'Gider', 'Kasa', 1, 5000, '2025-02-08', ''),
(6, 'Gider', 'Banka', 1, 5000, '2005-02-08', ''),
(7, 'Gider', 'Banka', NULL, 50000, '2025-02-08', ''),
(8, 'Gider', 'Banka', NULL, 50000, '2025-02-08', ''),
(9, 'Gider', 'Banka', NULL, 500000, '2025-05-05', ''),
(10, 'Gider', 'Banka', 2, 500000, '2025-02-08', ''),
(11, 'Gelir', 'Banka', 2, 500000, '2025-06-04', ''),
(12, 'Gelir', 'Kasa', 1, 5000, '2025-06-04', ''),
(13, 'Gelir', 'Kasa', NULL, 5000, '2025-06-03', ''),
(14, 'Gelir', 'Kasa', 1, 50000, '2025-06-01', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `giderler`
--

CREATE TABLE `giderler` (
  `id` int(11) NOT NULL,
  `tutar` decimal(10,2) NOT NULL,
  `aciklama` text DEFAULT NULL,
  `tarih` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `giris_loglari`
--

CREATE TABLE `giris_loglari` (
  `id` int(11) NOT NULL,
  `kullanici_adi` varchar(100) DEFAULT NULL,
  `ip_adresi` varchar(100) DEFAULT NULL,
  `tarayici` text DEFAULT NULL,
  `giris_zamani` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `giris_loglari`
--

INSERT INTO `giris_loglari` (`id`, `kullanici_adi`, `ip_adresi`, `tarayici`, `giris_zamani`) VALUES
(1, 'admin', '31.143.168.254', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', '2025-06-03 21:28:12'),
(2, 'admin', '85.108.148.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', '2025-06-03 22:39:54'),
(3, 'admin', '85.108.148.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-04 15:36:06'),
(4, 'admin', '85.108.148.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-04 18:33:53'),
(5, 'admin', '2a00:1880:a348:6da0:1845:ea2d:23cb:b1e6', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36', '2025-06-04 18:34:47'),
(6, 'admin', '88.242.62.127', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', '2025-06-04 18:35:33'),
(7, 'admin', '94.235.44.71', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Mobile Safari/537.36', '2025-06-04 18:39:51'),
(8, 'admin', '78.167.109.18', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36', '2025-06-04 18:40:49'),
(9, 'admin', '212.154.90.85', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-04 18:48:13'),
(10, 'admin', '176.41.128.176', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-04 18:49:03'),
(11, 'admin', '151.135.65.107', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Mobile Safari/537.36', '2025-06-04 18:53:20'),
(12, 'admin', '14.136.28.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-04 18:56:21'),
(13, 'admin', '94.54.30.126', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-04 18:57:34'),
(14, 'admin', '94.235.35.240', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', '2025-06-04 19:01:54'),
(15, 'admin', '78.190.159.171', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/137.0.7151.51 Mobile/15E148 Safari/604.1', '2025-06-04 20:08:16'),
(16, 'admin', '88.234.240.140', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Mobile Safari/537.36', '2025-06-04 20:49:41'),
(17, 'admin', '88.234.240.140', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', '2025-06-04 20:49:56'),
(18, 'admin', '185.155.148.248', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-04 20:49:57'),
(19, 'admin', '212.253.197.167', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-04 21:06:53'),
(20, 'admin', '46.1.148.106', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-04 21:35:31'),
(21, 'admin', '188.119.5.234', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-05 07:02:12'),
(22, 'admin', '94.54.242.38', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-06 20:38:20'),
(23, 'admin', '176.88.143.136', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36', '2025-06-09 15:31:22'),
(24, 'admin', '5.24.56.45', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-10 10:32:31'),
(25, 'admin', '5.24.56.45', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-10 12:44:49');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kasa`
--

CREATE TABLE `kasa` (
  `id` int(11) NOT NULL,
  `cari_id` int(11) DEFAULT NULL,
  `tutar` float DEFAULT NULL,
  `islem_tipi` varchar(10) DEFAULT NULL,
  `aciklama` text DEFAULT NULL,
  `tarih` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kasalar`
--

CREATE TABLE `kasalar` (
  `id` int(11) NOT NULL,
  `ad` varchar(100) NOT NULL,
  `bakiye` decimal(10,2) NOT NULL DEFAULT 0.00,
  `aciklama` varchar(255) DEFAULT NULL,
  `olusturma_tarihi` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `kasalar`
--

INSERT INTO `kasalar` (`id`, `ad`, `bakiye`, `aciklama`, `olusturma_tarihi`) VALUES
(1, 'Ana Kasa', -226000.00, 'Sistem tarafından oluşturuldu', '2025-06-03');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kasa_hareketler`
--

CREATE TABLE `kasa_hareketler` (
  `id` int(11) NOT NULL,
  `kasa_id` int(11) NOT NULL,
  `tutar` decimal(10,2) NOT NULL,
  `tip` enum('Giriş','Çıkış') NOT NULL,
  `aciklama` text DEFAULT NULL,
  `tarih` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kasa_hareketler`
--

INSERT INTO `kasa_hareketler` (`id`, `kasa_id`, `tutar`, `tip`, `aciklama`, `tarih`) VALUES
(1, 1, 800000.00, 'Giriş', '', '2025-02-08'),
(3, 1, 2000000.00, 'Giriş', '', '2025-02-01'),
(4, 1, 20000.00, 'Giriş', '', '2025-02-02'),
(5, 1, 250000.00, 'Giriş', '', '2025-05-08');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(100) DEFAULT NULL,
  `kullanici_adi` varchar(50) DEFAULT NULL,
  `sifre` varchar(255) DEFAULT NULL,
  `rol` varchar(20) DEFAULT NULL,
  `kayit_tarihi` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `ad_soyad`, `kullanici_adi`, `sifre`, `rol`, `kayit_tarihi`) VALUES
(1, 'kaan tiftik', 'admin', '$2y$10$unNXVIpMrZTVcj1kD7A6ZeBRL8pDjmtlXJXcuiM7fxNLYkgXtSj22', 'admin', '2025-06-03 21:25:50');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `maaslar`
--

CREATE TABLE `maaslar` (
  `id` int(11) NOT NULL,
  `personel_id` int(11) DEFAULT NULL,
  `ay` varchar(20) DEFAULT NULL,
  `yil` int(11) DEFAULT NULL,
  `odenen_tutar` float DEFAULT NULL,
  `durum` varchar(20) DEFAULT NULL,
  `aciklama` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personeller`
--

CREATE TABLE `personeller` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(100) DEFAULT NULL,
  `tc` varchar(20) DEFAULT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `gorev` varchar(100) DEFAULT NULL,
  `maas` float DEFAULT NULL,
  `giris_tarihi` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personel_giris`
--

CREATE TABLE `personel_giris` (
  `id` int(11) NOT NULL,
  `personel_id` int(11) DEFAULT NULL,
  `tarih` date DEFAULT NULL,
  `saat` time DEFAULT NULL,
  `tip` varchar(10) DEFAULT NULL,
  `aciklama` text DEFAULT NULL,
  `giris_saati` time DEFAULT NULL,
  `cikis_saati` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personel_maas`
--

CREATE TABLE `personel_maas` (
  `id` int(11) NOT NULL,
  `personel_id` int(11) DEFAULT NULL,
  `tutar` float DEFAULT NULL,
  `tarih` date DEFAULT NULL,
  `odeme_yolu` varchar(20) DEFAULT NULL,
  `odeme_id` int(11) DEFAULT NULL,
  `aciklama` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `personel_maas`
--

INSERT INTO `personel_maas` (`id`, `personel_id`, `tutar`, `tarih`, `odeme_yolu`, `odeme_id`, `aciklama`) VALUES
(1, 1, 23000, '2025-02-08', 'Kasa', 1, ''),
(2, 1, 23000, '2025-02-08', 'Kasa', 1, '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `satislar`
--

CREATE TABLE `satislar` (
  `id` int(11) NOT NULL,
  `cari_id` int(11) DEFAULT NULL,
  `stok_id` int(11) DEFAULT NULL,
  `miktar` float DEFAULT NULL,
  `birim_fiyat` float DEFAULT NULL,
  `toplam_tutar` float DEFAULT NULL,
  `islem_tarihi` date DEFAULT NULL,
  `aciklama` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `satislar`
--

INSERT INTO `satislar` (`id`, `cari_id`, `stok_id`, `miktar`, `birim_fiyat`, `toplam_tutar`, `islem_tarihi`, `aciklama`) VALUES
(1, 1, 1, 10000, 5000, 50000000, '2002-02-08', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `stoklar`
--

CREATE TABLE `stoklar` (
  `id` int(11) NOT NULL,
  `stok_adi` varchar(100) DEFAULT NULL,
  `stok_tipi` varchar(50) DEFAULT NULL,
  `birim` varchar(50) DEFAULT NULL,
  `alis_fiyat` float DEFAULT NULL,
  `satis_fiyat` float DEFAULT NULL,
  `miktar` float DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `aciklama` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `stoklar`
--

INSERT INTO `stoklar` (`id`, `stok_adi`, `stok_tipi`, `birim`, `alis_fiyat`, `satis_fiyat`, `miktar`, `kategori`, `aciklama`) VALUES
(1, 'kıyafet', 'Malzeme', 'adet', 100, 200, -9900, NULL, '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tasarruflar`
--

CREATE TABLE `tasarruflar` (
  `id` int(11) NOT NULL,
  `hedef_adi` varchar(100) DEFAULT NULL,
  `hedef_miktar` float DEFAULT NULL,
  `mevcut_miktar` float DEFAULT NULL,
  `hedef_tarih` date DEFAULT NULL,
  `aciklama` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yatirimlar`
--

CREATE TABLE `yatirimlar` (
  `id` int(11) NOT NULL,
  `tur` varchar(100) DEFAULT NULL,
  `miktar` float DEFAULT NULL,
  `tarih` date DEFAULT NULL,
  `aciklama` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `alislar`
--
ALTER TABLE `alislar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `ayarlar`
--
ALTER TABLE `ayarlar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `banka`
--
ALTER TABLE `banka`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `bankalar`
--
ALTER TABLE `bankalar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `banka_hareketler`
--
ALTER TABLE `banka_hareketler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `banka_islemleri`
--
ALTER TABLE `banka_islemleri`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `borclar`
--
ALTER TABLE `borclar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `butce`
--
ALTER TABLE `butce`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `cariler`
--
ALTER TABLE `cariler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `cari_hareketler`
--
ALTER TABLE `cari_hareketler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `faturalar`
--
ALTER TABLE `faturalar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `gelirler`
--
ALTER TABLE `gelirler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `gelir_gider`
--
ALTER TABLE `gelir_gider`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `giderler`
--
ALTER TABLE `giderler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `giris_loglari`
--
ALTER TABLE `giris_loglari`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kasa`
--
ALTER TABLE `kasa`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kasalar`
--
ALTER TABLE `kasalar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kasa_hareketler`
--
ALTER TABLE `kasa_hareketler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `maaslar`
--
ALTER TABLE `maaslar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `personeller`
--
ALTER TABLE `personeller`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `personel_giris`
--
ALTER TABLE `personel_giris`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `personel_maas`
--
ALTER TABLE `personel_maas`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `satislar`
--
ALTER TABLE `satislar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `stoklar`
--
ALTER TABLE `stoklar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `tasarruflar`
--
ALTER TABLE `tasarruflar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `yatirimlar`
--
ALTER TABLE `yatirimlar`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `alislar`
--
ALTER TABLE `alislar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `banka`
--
ALTER TABLE `banka`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `bankalar`
--
ALTER TABLE `bankalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `banka_hareketler`
--
ALTER TABLE `banka_hareketler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `banka_islemleri`
--
ALTER TABLE `banka_islemleri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `borclar`
--
ALTER TABLE `borclar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `butce`
--
ALTER TABLE `butce`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `cariler`
--
ALTER TABLE `cariler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `cari_hareketler`
--
ALTER TABLE `cari_hareketler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `faturalar`
--
ALTER TABLE `faturalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `gelirler`
--
ALTER TABLE `gelirler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `gelir_gider`
--
ALTER TABLE `gelir_gider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `giderler`
--
ALTER TABLE `giderler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `giris_loglari`
--
ALTER TABLE `giris_loglari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `kasa`
--
ALTER TABLE `kasa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `kasalar`
--
ALTER TABLE `kasalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `kasa_hareketler`
--
ALTER TABLE `kasa_hareketler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `maaslar`
--
ALTER TABLE `maaslar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `personeller`
--
ALTER TABLE `personeller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `personel_giris`
--
ALTER TABLE `personel_giris`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `personel_maas`
--
ALTER TABLE `personel_maas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `satislar`
--
ALTER TABLE `satislar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `stoklar`
--
ALTER TABLE `stoklar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `tasarruflar`
--
ALTER TABLE `tasarruflar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `yatirimlar`
--
ALTER TABLE `yatirimlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
