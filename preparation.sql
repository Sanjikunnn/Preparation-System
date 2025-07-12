-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for preparation
DROP DATABASE IF EXISTS `preparation`;
CREATE DATABASE IF NOT EXISTS `preparation` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `preparation`;

-- Dumping structure for table preparation.cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table preparation.cache: ~0 rows (approximately)

-- Dumping structure for table preparation.cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table preparation.cache_locks: ~0 rows (approximately)

-- Dumping structure for table preparation.distributes
DROP TABLE IF EXISTS `distributes`;
CREATE TABLE IF NOT EXISTS `distributes` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_hasil_upper` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_proses` timestamp NOT NULL,
  `no_barcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `barcode_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `distributes_id_hasil_upper_foreign` (`id_hasil_upper`),
  CONSTRAINT `distributes_id_hasil_upper_foreign` FOREIGN KEY (`id_hasil_upper`) REFERENCES `hasil_uppers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table preparation.distributes: ~3 rows (approximately)
REPLACE INTO `distributes` (`id`, `id_hasil_upper`, `waktu_proses`, `no_barcode`, `created_at`, `updated_at`, `barcode_image`) VALUES
	('001', '001', '2025-07-10 07:19:44', 'U-001-002-003-001-20250710141729-001-001', '2025-07-10 07:19:44', '2025-07-10 07:19:44', 'barcodes_distribute/distribute_barcode_001.png'),
	('002', '002', '2025-07-10 07:20:34', 'U-001-002-003-001-20250710141743-002-002', '2025-07-10 07:20:34', '2025-07-10 07:20:34', 'barcodes_distribute/distribute_barcode_002.png'),
	('003', '003', '2025-07-12 07:23:00', 'U-004-005-006-002-20250711133128-003-003', '2025-07-12 07:23:00', '2025-07-12 07:23:00', 'barcodes_distribute/distribute_barcode_003.png');

-- Dumping structure for table preparation.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table preparation.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table preparation.hasil_uppers
DROP TABLE IF EXISTS `hasil_uppers`;
CREATE TABLE IF NOT EXISTS `hasil_uppers` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_komponen_1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_komponen_2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_komponen_3` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_produk_jadi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_proses` timestamp NOT NULL,
  `no_barcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `distribute` tinyint(1) NOT NULL DEFAULT '0',
  `total_proses` int NOT NULL,
  `total_uppers` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hasil_uppers_id_komponen_1_foreign` (`id_komponen_1`),
  KEY `hasil_uppers_id_komponen_2_foreign` (`id_komponen_2`),
  KEY `hasil_uppers_id_komponen_3_foreign` (`id_komponen_3`),
  KEY `hasil_uppers_id_produk_jadi_foreign` (`id_produk_jadi`),
  CONSTRAINT `hasil_uppers_id_komponen_1_foreign` FOREIGN KEY (`id_komponen_1`) REFERENCES `komponen_produk_jadis` (`id`),
  CONSTRAINT `hasil_uppers_id_komponen_2_foreign` FOREIGN KEY (`id_komponen_2`) REFERENCES `komponen_produk_jadis` (`id`),
  CONSTRAINT `hasil_uppers_id_komponen_3_foreign` FOREIGN KEY (`id_komponen_3`) REFERENCES `komponen_produk_jadis` (`id`),
  CONSTRAINT `hasil_uppers_id_produk_jadi_foreign` FOREIGN KEY (`id_produk_jadi`) REFERENCES `produk_jadis` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table preparation.hasil_uppers: ~3 rows (approximately)
REPLACE INTO `hasil_uppers` (`id`, `id_komponen_1`, `id_komponen_2`, `id_komponen_3`, `id_produk_jadi`, `waktu_proses`, `no_barcode`, `barcode_image`, `distribute`, `total_proses`, `total_uppers`, `created_at`, `updated_at`) VALUES
	('001', '001', '002', '003', '001', '2025-07-10 07:17:29', 'U-001-002-003-001-20250710141729-001', 'barcodes/barcode_001.png', 1, 3, 0, '2025-07-10 07:17:29', '2025-07-10 07:19:44'),
	('002', '001', '002', '003', '001', '2025-07-10 07:17:43', 'U-001-002-003-001-20250710141743-002', 'barcodes/barcode_002.png', 1, 3, 0, '2025-07-10 07:17:43', '2025-07-10 07:20:34'),
	('003', '004', '005', '006', '002', '2025-07-11 06:31:28', 'U-004-005-006-002-20250711133128-003', 'barcodes/barcode_003.png', 1, 3, 0, '2025-07-11 06:31:28', '2025-07-12 07:23:00');

-- Dumping structure for table preparation.jobs
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table preparation.jobs: ~0 rows (approximately)

-- Dumping structure for table preparation.job_batches
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table preparation.job_batches: ~0 rows (approximately)

-- Dumping structure for table preparation.komponen_produk_jadis
DROP TABLE IF EXISTS `komponen_produk_jadis`;
CREATE TABLE IF NOT EXISTS `komponen_produk_jadis` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_komponen_produk_jadi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table preparation.komponen_produk_jadis: ~6 rows (approximately)
REPLACE INTO `komponen_produk_jadis` (`id`, `nama_komponen_produk_jadi`, `qty`, `created_at`, `updated_at`) VALUES
	('001', 'vamp nike', 6, '2025-07-10 05:28:45', '2025-07-10 05:28:45'),
	('002', 'backtub nike', 6, '2025-07-10 05:29:01', '2025-07-10 05:30:37'),
	('003', 'quarter nike', 6, '2025-07-10 05:29:13', '2025-07-10 05:29:13'),
	('004', 'vamp adidas', 6, '2025-07-11 06:21:42', '2025-07-11 06:23:12'),
	('005', 'backtub adidas', 6, '2025-07-11 06:21:56', '2025-07-11 06:23:21'),
	('006', 'quarter adidas', 6, '2025-07-11 06:22:10', '2025-07-11 06:23:27');

-- Dumping structure for table preparation.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table preparation.migrations: ~12 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(8, '0001_01_01_000000_create_users_table', 1),
	(9, '0001_01_01_000001_create_cache_table', 1),
	(10, '0001_01_01_000002_create_jobs_table', 1),
	(11, '2025_07_09_145658_create_komponen_produk_jadis_table', 1),
	(12, '2025_07_09_150101_create_produk_jadis_table', 1),
	(13, '2025_07_09_150219_create_hasil_uppers_table', 1),
	(14, '2025_07_09_150739_create_distributes_table', 1),
	(15, '2025_07_09_160902_create_sessions_table', 2),
	(16, '2025_07_10_122353_add_barcode_image_to_hasil_upper_table', 3),
	(17, '2025_07_10_125121_add_barcode_image_to_distributes_table', 4),
	(18, '2025_07_10_131154_add_distribute_to_hasil_upper_table', 5),
	(19, '2025_07_11_114317_add_total_upper_to_produk_jadis_table', 6);

-- Dumping structure for table preparation.produk_jadis
DROP TABLE IF EXISTS `produk_jadis`;
CREATE TABLE IF NOT EXISTS `produk_jadis` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_produk_jadi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_komponen_produk_jadi_1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_komponen_produk_jadi_2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_komponen_produk_jadi_3` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produk_jadis_id_komponen_produk_jadi_1_foreign` (`id_komponen_produk_jadi_1`),
  KEY `produk_jadis_id_komponen_produk_jadi_2_foreign` (`id_komponen_produk_jadi_2`),
  KEY `produk_jadis_id_komponen_produk_jadi_3_foreign` (`id_komponen_produk_jadi_3`),
  CONSTRAINT `produk_jadis_id_komponen_produk_jadi_1_foreign` FOREIGN KEY (`id_komponen_produk_jadi_1`) REFERENCES `komponen_produk_jadis` (`id`),
  CONSTRAINT `produk_jadis_id_komponen_produk_jadi_2_foreign` FOREIGN KEY (`id_komponen_produk_jadi_2`) REFERENCES `komponen_produk_jadis` (`id`),
  CONSTRAINT `produk_jadis_id_komponen_produk_jadi_3_foreign` FOREIGN KEY (`id_komponen_produk_jadi_3`) REFERENCES `komponen_produk_jadis` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table preparation.produk_jadis: ~2 rows (approximately)
REPLACE INTO `produk_jadis` (`id`, `nama_produk_jadi`, `id_komponen_produk_jadi_1`, `id_komponen_produk_jadi_2`, `id_komponen_produk_jadi_3`, `qty`, `created_at`, `updated_at`) VALUES
	('001', 'sepatu nike', '001', '002', '003', 3, '2025-07-10 05:29:44', '2025-07-11 06:23:36'),
	('002', 'sepatu adidas', '004', '005', '006', 3, '2025-07-11 06:22:41', '2025-07-11 06:22:41');

-- Dumping structure for table preparation.sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table preparation.sessions: ~1 rows (approximately)
REPLACE INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('txIPV1U1B5IKr2MHfywBXFXv0YpYFuk8RZhUiiLD', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoia3ZwZEZmRWE4NnB2MHZiRkNlUm5vSlh5TFNKZHRJWWtxMnR4Mnk1RiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kaXN0cmlidXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2tvbXBvbmVuX3Byb2R1a19qYWRpIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO3M6MzoiMDAxIjt9', 1752330314);

-- Dumping structure for table preparation.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('operator','supervisor') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`nik`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table preparation.users: ~2 rows (approximately)
REPLACE INTO `users` (`nik`, `nama`, `password`, `role`, `created_at`, `updated_at`) VALUES
	('001', 'Sonia', '$2y$12$tmS9J5QYsUkq3jWMFfJjReRFIRxTJvYY.sdUwmyIhGq/mZ52gVpxW', 'operator', '2025-07-09 09:29:52', '2025-07-09 09:29:52'),
	('002', 'Iqbal', '$2y$12$BjuzGku2dTVzmdzIyLvh7.Ti82q8nH/awcBn203teFQ5GE.x.ZULm', 'supervisor', '2025-07-09 09:29:52', '2025-07-09 09:29:52');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
