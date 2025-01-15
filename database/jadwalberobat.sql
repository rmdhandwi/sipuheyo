-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 15, 2025 at 09:07 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jadwalberobat`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dokters`
--

CREATE TABLE `dokters` (
  `id` bigint UNSIGNED NOT NULL,
  `nid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jk` enum('Laki-Laki','Perempuan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Laki-Laki',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `spesialis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kontak` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dokters`
--

INSERT INTO `dokters` (`id`, `nid`, `nama`, `jk`, `email`, `spesialis`, `kontak`, `user_id`) VALUES
(3, '453453454534', 'Dokter 1', 'Laki-Laki', 'zilivorugi@mailinator.com', 'Penyakit 1', '635345435345', 6),
(4, '433434343434', 'Dokter 2', 'Laki-Laki', 'zyzecar@mailinator.com', 'Penyakit 2', '7553454354353', 7),
(5, '123456789876', 'Dokter 3', 'Laki-Laki', 'pumy@mailinator.com', 'Penyakit 3', '5943412343413', 8),
(6, '453454534545', 'Dokter 4', 'Perempuan', 'qozokin@mailinator.com', 'Penyakit 4', '933434342342', 9);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_berobats`
--

CREATE TABLE `jadwal_berobats` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `obats`
--

CREATE TABLE `obats` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merek` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dosis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kemasan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `obats`
--

INSERT INTO `obats` (`id`, `nama`, `merek`, `dosis`, `kemasan`, `deskripsi`) VALUES
(1, 'Et enim porro impedi', 'Eveniet voluptatem', 'Velit minim quibusd', 'Rerum incidunt nihi', 'Anim dolor dicta ali'),
(2, 'Perferendis qui saep', 'Tempora enim distinc', 'Nostrud porro delect', 'Similique quia volup', 'Magni minim voluptas');

-- --------------------------------------------------------

--
-- Table structure for table `pasiens`
--

CREATE TABLE `pasiens` (
  `id` bigint UNSIGNED NOT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jk` enum('Laki-Laki','Perempuan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Laki-Laki',
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `kontak` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pasiens`
--

INSERT INTO `pasiens` (`id`, `nik`, `email`, `nama`, `jk`, `tempat_lahir`, `tanggal_lahir`, `kontak`, `alamat`, `user_id`) VALUES
(3, '6565656535635565', 'xelucez@mailinator.com', 'Pasien 1', 'Perempuan', 'In eveniet error el', '1990-02-16', '082238458937', 'Commodo exercitation', '10'),
(4, '1212121212121212', 'pasien@123.tech', 'Pasien 2', 'Laki-Laki', 'Dimana saja', '2016-07-25', '082253304042', 'Dimana saja', '11');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pegawais`
--

CREATE TABLE `pegawais` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jk` enum('Laki-Laki','Perempuan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Laki-Laki',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bagian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kontak` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pegawais`
--

INSERT INTO `pegawais` (`id`, `nama`, `jk`, `email`, `bagian`, `kontak`, `user_id`) VALUES
(1, 'Pegawai 1', 'Perempuan', 'zovymep@mailinator.com', 'Bagian 2', '43434345353', '3'),
(2, 'Pegawai 2', 'Laki-Laki', 'hylewob@mailinator.com', 'Bagian 2', '353524543254', '4'),
(3, 'Pegawai 3', 'Perempuan', 'fogugunofo@mailinator.com', 'Bagian 3', '455243524554', '5'),
(4, 'Pegawai 4', 'Laki-Laki', 'catadih@mailinator.com', 'Ea et facere accusan', '5435345435435', '12'),
(5, 'Pegawai 5', 'Perempuan', 'wuhimapeq@mailinator.com', 'Dolor non aliqua Fu', '9745636356354', '13'),
(6, 'Pegawai 6', 'Laki-Laki', 'sigupe@mailinator.com', 'Quia vero error corr', '8045245245345', '14'),
(7, 'Pegawai 7', 'Laki-Laki', 'jidacywe@mailinator.com', 'Quod repellendus Qu', '893454352354', '15'),
(8, 'Pegawai 8', 'Perempuan', 'lumyqu@mailinator.com', 'Odit quia laudantium', '325325452343', '16');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `polis`
--

CREATE TABLE `polis` (
  `id` bigint UNSIGNED NOT NULL,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penyakit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dokter_id` bigint UNSIGNED NOT NULL,
  `pegawai_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `polis`
--

INSERT INTO `polis` (`id`, `kode`, `nama`, `penyakit`, `keterangan`, `dokter_id`, `pegawai_id`) VALUES
(1, 'PD001', 'Poli Dewasa', 'Penyakit 1', 'Non nulla veniam ex', 3, 1),
(2, 'PA001', 'Poli Anak', 'Penyakit 2', 'In aliquam nihil opt', 4, 2),
(7, 'PGM001', 'Poli Gigi & Mulut', 'Penyakit 3', 'dfdafadf', 5, 3),
(8, 'RK001', 'Ruang KIA/KB', 'Penyakit 4', 'fdafdafdfa', 6, 4);

-- --------------------------------------------------------

--
-- Table structure for table `rekam_mediks`
--

CREATE TABLE `rekam_mediks` (
  `id` bigint UNSIGNED NOT NULL,
  `antrian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date NOT NULL,
  `pasien_id` int NOT NULL,
  `dokter_id` int NOT NULL,
  `poli_id` int NOT NULL,
  `kondisi` json DEFAULT NULL,
  `keluhan` json DEFAULT NULL,
  `penanganan` json DEFAULT NULL,
  `resep` json DEFAULT NULL,
  `hasil_lab` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `konsultasi_berikut` datetime DEFAULT NULL,
  `kirimpesan1` datetime DEFAULT NULL,
  `kirimpesan2` datetime DEFAULT NULL,
  `status` enum('baru','dokter') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baru',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rekam_mediks`
--

INSERT INTO `rekam_mediks` (`id`, `antrian`, `tanggal`, `pasien_id`, `dokter_id`, `poli_id`, `kondisi`, `keluhan`, `penanganan`, `resep`, `hasil_lab`, `konsultasi_berikut`, `kirimpesan1`, `kirimpesan2`, `status`, `created_at`, `updated_at`) VALUES
(3, 'PD001-15012025-001', '2025-01-15', 4, 3, 1, NULL, '[]', '[]', '[]', NULL, NULL, NULL, NULL, 'baru', '2025-01-14 20:27:14', '2025-01-14 20:27:14');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('84WiBZ13fHdzHtAUblZwHnzPvptIOohrrDFmPWco', 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQjhSTER4Wkt0S0EzbUliNWRWWmF4dGNRU0E1TWd3VDlvdG1SMTZjYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wYXNpZW4/Mz0iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMTt9', 1736923328),
('mTdTXMUBM2Q97FTIcHX6Heha0zhRXtevt9zCnDj0', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSEFXVG9VNXlnQ1IwV093VEhkVWs1WHRrUFJRSm54em9xOXFpaHRyTCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vZG9rdGVyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1736923327),
('xvbsulSVSoYEZp6k1U46QDH62FPFk6f96FjjpY6K', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidWx3V3phd3UxVjRoOWhiaHY4V1NvNFZRTlU4VWM5d3h1QUV3YzBXTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kb2t0ZXIvcGFzaWVuLzQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=', 1736925994);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','dokter','pegawai','pasien') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Adminisator', 'admin@admin.tech', NULL, '$2y$12$b1dpnBzicBbpGorIKO.Gw.gRSrl75GFO4xbK.557APYykk2giAzbK', 'admin', NULL, '2024-11-22 11:46:19', '2025-01-13 01:04:33'),
(2, 'Illum amet fugiat', 'fome@mailinator.com', NULL, '$2y$12$otglZXYFKD443bx.DUYNFe3IAbKdGZ16wqA2YlSPtJZRACrONBESe', 'dokter', NULL, '2025-01-13 18:09:25', '2025-01-13 18:09:25'),
(3, 'Dolorum quia autem n', 'zovymep@mailinator.com', NULL, '$2y$12$esnm2aBrgPLAYwxpAKPo1eHS2bfkd7F1SFNj..Yfa5/elpfXONEcu', 'pegawai', NULL, '2025-01-14 03:10:07', '2025-01-14 03:10:07'),
(4, 'Earum aliquam delect', 'hylewob@mailinator.com', NULL, '$2y$12$GPYyymVG18T7whEa5ANvSOcRg6fYknZKq7gh4Munw9BnjvHxLwrL6', 'pegawai', NULL, '2025-01-14 03:10:15', '2025-01-14 03:10:15'),
(5, 'Voluptas commodo vel', 'fogugunofo@mailinator.com', NULL, '$2y$12$pGTZwzlR8nOb6ri9ga.LQ.d3yKX.0UB.5IjsR1RvCCt0kvSrGF7FK', 'pegawai', NULL, '2025-01-14 03:10:24', '2025-01-14 03:10:24'),
(6, 'Consequuntur occaeca', 'zilivorugi@mailinator.com', NULL, '$2y$12$5pePsZumpkOtFN4ih5oy0uK2VDPA6u82q5JyqBpZoJaDisEqu2Y9y', 'dokter', NULL, '2025-01-14 03:15:16', '2025-01-14 03:15:16'),
(7, 'Totam sint autem nul', 'zyzecar@mailinator.com', NULL, '$2y$12$9jIjza/dalQnNhe95bcZluY7V6QfnM3PCtnhSc9jzFTyIsuihCWOG', 'dokter', NULL, '2025-01-14 03:15:39', '2025-01-14 03:15:39'),
(8, 'Et quidem sint dese', 'pumy@mailinator.com', NULL, '$2y$12$CwKzBqBRIaxvmqdco6b9QOOxvUSASmdMKZoWmksE3XVV1shraWdGu', 'dokter', NULL, '2025-01-14 03:16:10', '2025-01-14 03:16:10'),
(9, 'Consequatur est et', 'qozokin@mailinator.com', NULL, '$2y$12$21N9ZN0sNKO8oC1suqn2K.8TXkavDn4gMax/pwl7uNj7T2D/Wt1Aq', 'dokter', NULL, '2025-01-14 03:25:21', '2025-01-14 03:25:21'),
(10, 'Est corrupti nobis', 'xelucez@mailinator.com', NULL, '$2y$12$Xhr9X1dqKpWK0Izekfcb7.qiMnhByUOaHQ3ZVHuSXkkMqPFFQf/9a', 'pasien', NULL, '2025-01-14 03:33:23', '2025-01-14 03:33:23'),
(11, 'Saya Ini Sudah', 'pasien@123.tech', NULL, '$2y$12$NbI/TES1KrHJxcwMDfUpS.C.bfZjcHmqcUX6/o2/KNwPiRqdzzruK', 'pasien', NULL, '2025-01-14 12:13:45', '2025-01-14 12:13:45'),
(12, 'Pegawai 4', 'catadih@mailinator.com', NULL, '$2y$12$orpudDcKblbj3r3GH91y.eaYB94AhXDIZBH2Jzf668./Va5SKr0Qi', 'pegawai', NULL, '2025-01-14 16:11:15', '2025-01-14 16:11:15'),
(13, 'Pegawai 5', 'wuhimapeq@mailinator.com', NULL, '$2y$12$Qu8/IGVLd/a6j4O7F9.u2.QC2OobX4WjexfdiQ2e3KjKLAB2sySKu', 'pegawai', NULL, '2025-01-14 16:11:33', '2025-01-14 16:11:33'),
(14, 'Pegawai 6', 'sigupe@mailinator.com', NULL, '$2y$12$UeJN35DbmSF9J6UW1h59ZO8qH6aPms0Q2w3dJ3x39faz7pn79T2mW', 'pegawai', NULL, '2025-01-14 16:11:51', '2025-01-14 16:11:51'),
(15, 'Pegawai 7', 'jidacywe@mailinator.com', NULL, '$2y$12$IfVqT8J3f/Pn4UuZKXG/SulESZJp/JeZ9uFm7cCDqJ.nXBXHu1P2O', 'pegawai', NULL, '2025-01-14 16:12:00', '2025-01-14 16:12:00'),
(16, 'Pegawai 8', 'lumyqu@mailinator.com', NULL, '$2y$12$xyD/X7Jb3n3GX9RqnosFoOMOn8Qo7k97E5uSOupfGaY7Zg7eS/WdC', 'pegawai', NULL, '2025-01-14 16:12:18', '2025-01-14 16:12:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `dokters`
--
ALTER TABLE `dokters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jadwal_berobats`
--
ALTER TABLE `jadwal_berobats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `obats`
--
ALTER TABLE `obats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pasiens`
--
ALTER TABLE `pasiens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pasiens_nik_unique` (`nik`),
  ADD UNIQUE KEY `pasiens_email_unique` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pegawais`
--
ALTER TABLE `pegawais`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `polis`
--
ALTER TABLE `polis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `rekam_mediks`
--
ALTER TABLE `rekam_mediks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dokters`
--
ALTER TABLE `dokters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_berobats`
--
ALTER TABLE `jadwal_berobats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `obats`
--
ALTER TABLE `obats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pasiens`
--
ALTER TABLE `pasiens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pegawais`
--
ALTER TABLE `pegawais`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `polis`
--
ALTER TABLE `polis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `rekam_mediks`
--
ALTER TABLE `rekam_mediks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
