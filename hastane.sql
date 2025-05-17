-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 22 Eki 2024, 21:21:54
-- Sunucu sürümü: 8.2.0
-- PHP Sürümü: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `hastane`
--
CREATE DATABASE IF NOT EXISTS `hastane` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci;
USE `hastane`;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bulten`
--

DROP TABLE IF EXISTS `bulten`;
CREATE TABLE IF NOT EXISTS `bulten` (
  `id` int NOT NULL AUTO_INCREMENT,
  `eposta` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_eposta` (`eposta`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `doktorlar`
--

DROP TABLE IF EXISTS `doktorlar`;
CREATE TABLE IF NOT EXISTS `doktorlar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `isim_soyisim` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `telefon` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `bolum_id` int DEFAULT NULL,
  `create_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `kullaniciadi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `parola` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bolum_id` (`bolum_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `doktorlar`
--

INSERT INTO `doktorlar` (`id`, `isim_soyisim`, `telefon`, `bolum_id`, `create_date`, `kullaniciadi`, `parola`) VALUES
(31, 'Yiğit Dumlu', '0 (555) 555 55 55', 1, '2024-10-22 09:25:18', 'yigitdumlu', '123456'),
(8, 'Mehmet Demir', NULL, 2, '2024-10-22 09:25:18', '', ''),
(10, 'Veli Şahin', NULL, 4, '2024-10-22 09:25:18', '', ''),
(11, 'Hüseyin Aydın', NULL, 5, '2024-10-22 09:25:18', '', ''),
(12, 'Osman Arslan', NULL, 6, '2024-10-22 09:25:18', '', ''),
(13, 'Fatma Yıldırım', NULL, 7, '2024-10-22 09:25:18', '', ''),
(14, 'Ayşe Polat', NULL, 8, '2024-10-22 09:25:18', '', ''),
(15, 'Elif Kaya', NULL, 9, '2024-10-22 09:25:18', '', ''),
(16, 'Zeynep Öztürk', NULL, 10, '2024-10-22 09:25:18', '', ''),
(17, 'Ahmet Yılmaz', NULL, 11, '2024-10-22 09:25:18', '', ''),
(18, 'Mehmet Demir', NULL, 12, '2024-10-22 09:25:18', '', ''),
(20, 'Veli Şahin', NULL, 14, '2024-10-22 09:25:18', '', ''),
(21, 'Hüseyin Aydın', NULL, 15, '2024-10-22 09:25:18', '', ''),
(22, 'Osman Arslan', NULL, 16, '2024-10-22 09:25:18', '', ''),
(23, 'Fatma Yıldırım', NULL, 17, '2024-10-22 09:25:18', '', ''),
(24, 'Ayşe Polat', NULL, 18, '2024-10-22 09:25:18', '', ''),
(25, 'Elif Kaya', NULL, 19, '2024-10-22 09:25:18', '', ''),
(26, 'Zeynep Öztürk', NULL, 20, '2024-10-22 09:25:18', '', ''),
(27, 'Ahmet Yılmaz', NULL, 21, '2024-10-22 09:25:18', '', ''),
(28, 'Mehmet Demir', NULL, 22, '2024-10-22 09:25:18', '', ''),
(35, 'Ahmet Ege', NULL, 1, '2024-10-22 09:27:30', '', ''),
(40, 'Ali Çelik', '0 (000) 000 00 00', 3, '2024-10-22 23:57:11', 'alicelik', '399934');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `poliklinikler`
--

DROP TABLE IF EXISTS `poliklinikler`;
CREATE TABLE IF NOT EXISTS `poliklinikler` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `poliklinikler`
--

INSERT INTO `poliklinikler` (`id`, `ad`) VALUES
(1, 'İç Hastalıkları'),
(2, 'Kardiyoloji'),
(3, 'Dermatoloji'),
(4, 'Genel Cerrahi'),
(5, 'Plastik Cerrahi'),
(6, 'Diş Cerrahi'),
(7, 'Psikoloji'),
(8, 'Diyetisyen'),
(9, 'Göz Hastalıkları'),
(10, 'Üroloji'),
(11, 'Nöroloji'),
(12, 'Kulak Burun Boğaz'),
(13, 'Ortopedi ve Travmatoloji'),
(14, 'Fizik Tedavi ve Rehabilitasyon'),
(15, 'Kadın Hastalıkları ve Doğum'),
(16, 'Çocuk Sağlığı ve Hastalıkları'),
(17, 'Radyoloji'),
(18, 'Anestezi ve Reanimasyon'),
(19, 'Nefroloji'),
(20, 'Endokrinoloji'),
(21, 'Hematoloji'),
(22, 'Onkoloji'),
(23, 'Enfeksiyon Hastalıkları'),
(24, 'Romatoloji');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `randevular`
--

DROP TABLE IF EXISTS `randevular`;
CREATE TABLE IF NOT EXISTS `randevular` (
  `id` int NOT NULL AUTO_INCREMENT,
  `isim_soyisim` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `tel_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `polikinlik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `doktor_id` int DEFAULT NULL,
  `tc` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `tarih` date DEFAULT NULL,
  `saat` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci,
  `sikayet` text CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci,
  PRIMARY KEY (`id`),
  KEY `doktor_id` (`doktor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `randevular`
--

INSERT INTO `randevular` (`id`, `isim_soyisim`, `tel_no`, `polikinlik`, `doktor_id`, `tc`, `tarih`, `saat`, `sikayet`) VALUES
(13, 'Ayşe Polat', '5558901234', 'Diyetisyen', 31, '12345678908', '2024-10-18', '17:00', 'Kilo verme'),
(14, 'Elif Kaya', '5559012345', 'Göz Hastalıkları', 9, '12345678909', '2024-10-19', '09:30', 'Görme bozukluğu'),
(15, 'Osman Öztürk', '5550123456', 'Üroloji', 10, '12345678910', '2024-10-19', '10:30', 'Böbrek taşı');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `randevu_saatleri`
--

DROP TABLE IF EXISTS `randevu_saatleri`;
CREATE TABLE IF NOT EXISTS `randevu_saatleri` (
  `id` int NOT NULL AUTO_INCREMENT,
  `saat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `randevu_saatleri`
--

INSERT INTO `randevu_saatleri` (`id`, `saat`) VALUES
(1, '08:30'),
(2, '09:30'),
(3, '10:30'),
(4, '11:30'),
(5, '12:30'),
(6, '13:30'),
(7, '14:30'),
(8, '15:30'),
(9, '16:30'),
(10, '17:30');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yetkililer`
--

DROP TABLE IF EXISTS `yetkililer`;
CREATE TABLE IF NOT EXISTS `yetkililer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kullaniciadi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `parola` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kullaniciadi` (`kullaniciadi`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `yetkililer`
--

INSERT INTO `yetkililer` (`id`, `kullaniciadi`, `parola`) VALUES
(1, 'admin', '12345');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
