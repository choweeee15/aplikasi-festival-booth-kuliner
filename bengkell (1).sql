-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2025 at 02:34 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bengkell`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_kendaraan`
--

CREATE TABLE `kategori_kendaraan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_kendaraan`
--

INSERT INTO `kategori_kendaraan` (`id`, `nama_kategori`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Kendaraan Roda 4', 'aktif', '2025-05-27 08:59:44', '2025-05-27 08:59:44'),
(2, 'Kendaraan Roda 2', 'aktif', '2025-05-27 08:59:49', '2025-05-27 08:59:49'),
(3, 'Truk', 'aktif', '2025-05-27 14:58:31', '2025-05-28 01:08:20'),
(4, 'Becak', 'nonaktif', '2025-05-27 14:58:36', '2025-05-27 14:58:36'),
(5, 'Kendaraan Roda 6', 'aktif', '2025-05-27 14:58:48', '2025-05-27 14:58:48'),
(6, 'dwadsad', 'nonaktif', '2025-06-01 05:31:47', '2025-06-01 05:32:01');

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_layanan` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `estimasi` varchar(255) NOT NULL,
  `status` enum('aktif','tidak aktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`id`, `nama_layanan`, `harga`, `estimasi`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ganti Oli', 843254523, '120', 'aktif', '2025-05-27 09:00:37', '2025-05-27 09:00:37'),
(2, 'Tune Up Mesin', 150000, '2 jam', 'aktif', NULL, NULL),
(3, 'Service Rem', 100000, '1.5 jam', 'aktif', NULL, NULL),
(4, 'Ganti Aki', 250000, '30 menit', 'aktif', NULL, NULL),
(5, 'Lainnya (Ketik Di Catatan)', 200000, '52', 'aktif', NULL, '2025-05-27 15:01:00'),
(6, 'tes', 4567800, '80', 'aktif', '2025-05-28 01:08:03', '2025-05-28 01:08:03');

-- --------------------------------------------------------

--
-- Table structure for table `log_activities`
--

CREATE TABLE `log_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `activity` text NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `log_activities`
--

INSERT INTO `log_activities` (`id`, `username`, `activity`, `ip_address`, `created_at`) VALUES
(1, 'admin@gmail.com', 'User logout dengan email: admin@gmail.com', '127.0.0.1', '2025-05-28 04:34:31'),
(2, 'admin@gmail.com', 'User login dengan email: admin@gmail.com', '127.0.0.1', '2025-05-28 04:34:43'),
(3, 'admin@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-05-28 04:39:53'),
(4, 'admin@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-05-28 04:48:04'),
(5, 'admin@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-05-28 04:48:18'),
(6, 'admin@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-05-28 04:49:47'),
(7, 'admin@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-05-28 04:50:24'),
(8, 'admin@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-05-28 04:50:25'),
(9, 'admin@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-05-28 04:55:46'),
(10, 'admin@gmail.com', 'Melihat riwayat pembayaran.', '127.0.0.1', '2025-05-28 04:55:47'),
(11, 'admin@gmail.com', 'User login dengan email: admin@gmail.com', '127.0.0.1', '2025-05-30 00:26:17'),
(12, 'admin@gmail.com', 'User login dengan email: admin@gmail.com', '127.0.0.1', '2025-05-31 13:29:37'),
(13, 'pengguna@gmail.com', 'User login dengan email: pengguna@gmail.com', '127.0.0.1', '2025-05-31 13:30:12'),
(14, 'pengguna@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-05-31 13:30:17'),
(15, 'pengguna@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-05-31 13:31:25'),
(16, 'pengguna@gmail.com', 'Melihat riwayat pembayaran.', '127.0.0.1', '2025-05-31 13:31:35'),
(17, 'pengguna@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-05-31 13:31:40'),
(18, 'pengguna@gmail.com', 'User logout dengan email: pengguna@gmail.com', '127.0.0.1', '2025-05-31 13:31:44'),
(19, 'pengguna@gmail.com', 'User login dengan email: pengguna@gmail.com', '127.0.0.1', '2025-05-31 13:34:12'),
(20, 'pengguna@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-05-31 13:34:13'),
(21, 'pengguna@gmail.com', 'Melakukan booking untuk kendaraan: toyota ()', '127.0.0.1', '2025-05-31 13:34:47'),
(22, 'pengguna@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-05-31 13:34:51'),
(23, 'pengguna@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-05-31 13:34:55'),
(24, 'pengguna@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-05-31 13:35:06'),
(25, 'pengguna@gmail.com', 'Memperbarui status permintaan layanan ID: 7 menjadi: dikonfirmasi', '127.0.0.1', '2025-05-31 13:35:42'),
(26, 'pengguna@gmail.com', 'Mengonfirmasi permintaan layanan ID: 7 dan menambahkan tagihan sebesar: 650000', '127.0.0.1', '2025-05-31 13:35:42'),
(27, 'pengguna@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-05-31 13:35:42'),
(28, 'pengguna@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-05-31 13:35:47'),
(29, 'pengguna@gmail.com', 'Melihat riwayat pembayaran.', '127.0.0.1', '2025-05-31 13:36:13'),
(30, 'pengguna@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-05-31 13:36:46'),
(31, 'pengguna@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-05-31 13:36:48'),
(32, 'pengguna@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-05-31 13:37:26'),
(33, 'pengguna@gmail.com', 'Admin melihat riwayat pembayaran.', '127.0.0.1', '2025-05-31 13:38:33'),
(34, 'pengguna@gmail.com', 'Admin melihat riwayat pembayaran.', '127.0.0.1', '2025-05-31 13:38:42'),
(35, 'pengguna@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-05-31 13:57:52'),
(36, 'admin@gmail.com', 'User login dengan email: admin@gmail.com', '127.0.0.1', '2025-05-31 14:00:25'),
(37, 'admin@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-05-31 14:00:29'),
(38, 'admin@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-05-31 14:33:41'),
(39, 'admin@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-05-31 14:35:21'),
(40, 'admin@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-05-31 14:35:56'),
(41, 'admin@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-05-31 14:53:36'),
(42, 'admin@gmail.com', 'Admin melihat riwayat pembayaran.', '127.0.0.1', '2025-05-31 15:05:29'),
(43, 'admin@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-05-31 15:13:36'),
(44, 'admin@gmail.com', 'User logout dengan email: admin@gmail.com', '127.0.0.1', '2025-05-31 15:13:38'),
(45, 'pengguna@gmail.com', 'User login dengan email: pengguna@gmail.com', '127.0.0.1', '2025-05-31 15:13:56'),
(46, 'pengguna@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-05-31 15:13:57'),
(47, 'pengguna@gmail.com', 'Melihat riwayat pembayaran.', '127.0.0.1', '2025-05-31 15:15:26'),
(48, 'Guest', 'Mendaftarkan user baru dengan email: rpl@gmail.com', '127.0.0.1', '2025-06-01 04:48:06'),
(49, 'rpl@gmail.com', 'User login dengan email: rpl@gmail.com', '127.0.0.1', '2025-06-01 04:48:33'),
(50, 'rpl@gmail.com', 'User logout dengan email: rpl@gmail.com', '127.0.0.1', '2025-06-01 04:48:50'),
(51, 'admin@gmail.com', 'User login dengan email: admin@gmail.com', '127.0.0.1', '2025-06-01 04:49:01'),
(52, 'admin@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-06-01 04:49:06'),
(53, 'admin@gmail.com', 'User logout dengan email: admin@gmail.com', '127.0.0.1', '2025-06-01 04:49:46'),
(54, 'admin@gmail.com', 'User login dengan email: admin@gmail.com', '127.0.0.1', '2025-06-01 04:50:04'),
(55, 'admin@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-06-01 04:50:04'),
(56, 'admin@gmail.com', 'User logout dengan email: admin@gmail.com', '127.0.0.1', '2025-06-01 04:50:42'),
(57, 'Guest', 'Mendaftarkan user baru dengan email: sapi@gmail.com', '127.0.0.1', '2025-06-01 04:53:14'),
(58, 'sapi@gmail.com', 'User login dengan email: sapi@gmail.com', '127.0.0.1', '2025-06-01 04:53:43'),
(59, 'sapi@gmail.com', 'User logout dengan email: sapi@gmail.com', '127.0.0.1', '2025-06-01 04:53:58'),
(60, 'admin@gmail.com', 'User login dengan email: admin@gmail.com', '127.0.0.1', '2025-06-01 04:54:42'),
(61, 'admin@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-06-01 04:54:42'),
(62, 'admin@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 04:54:57'),
(63, 'admin@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-06-01 04:54:58'),
(64, 'admin@gmail.com', 'Melakukan booking untuk kendaraan: Toyota (sedan)', '127.0.0.1', '2025-06-01 04:57:35'),
(65, 'admin@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 04:57:51'),
(66, 'admin@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-06-01 04:58:26'),
(67, 'admin@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 04:58:29'),
(68, 'admin@gmail.com', 'Memperbarui status permintaan layanan ID: 8 menjadi: dikonfirmasi', '127.0.0.1', '2025-06-01 04:59:10'),
(69, 'admin@gmail.com', 'Mengonfirmasi permintaan layanan ID: 8 dan menambahkan tagihan sebesar: 780000', '127.0.0.1', '2025-06-01 04:59:10'),
(70, 'admin@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 04:59:10'),
(71, 'admin@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 04:59:18'),
(72, 'admin@gmail.com', 'Admin mengupload bukti pembayaran untuk registrasi ID: 8', '127.0.0.1', '2025-06-01 05:00:00'),
(73, 'admin@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:00:00'),
(74, 'admin@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:02:08'),
(75, 'admin@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:02:26'),
(76, 'admin@gmail.com', 'User logout dengan email: admin@gmail.com', '127.0.0.1', '2025-06-01 05:02:31'),
(77, 'Guest', 'Mendaftarkan user baru dengan email: kudanil@gmail.com', '127.0.0.1', '2025-06-01 05:03:55'),
(78, 'kudanil@gmail.com', 'User login dengan email: kudanil@gmail.com', '127.0.0.1', '2025-06-01 05:04:32'),
(79, 'kudanil@gmail.com', 'User logout dengan email: kudanil@gmail.com', '127.0.0.1', '2025-06-01 05:04:48'),
(80, 'admin@gmail.com', 'User login dengan email: admin@gmail.com', '127.0.0.1', '2025-06-01 05:05:03'),
(81, 'admin@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-06-01 05:05:03'),
(82, 'admin@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 05:05:14'),
(83, 'admin@gmail.com', 'Admin melihat riwayat pembayaran.', '127.0.0.1', '2025-06-01 05:05:19'),
(84, 'admin@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-06-01 05:05:22'),
(85, 'admin@gmail.com', 'Melakukan booking untuk kendaraan: Toyota (Sedan)', '127.0.0.1', '2025-06-01 05:06:52'),
(86, 'admin@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:07:07'),
(87, 'admin@gmail.com', 'User logout dengan email: admin@gmail.com', '127.0.0.1', '2025-06-01 05:07:22'),
(88, 'kudanil@gmail.com', 'User login dengan email: kudanil@gmail.com', '127.0.0.1', '2025-06-01 05:07:37'),
(89, 'kudanil@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:07:38'),
(90, 'kudanil@gmail.com', 'User logout dengan email: kudanil@gmail.com', '127.0.0.1', '2025-06-01 05:07:55'),
(91, 'Guest', 'Mendaftarkan user baru dengan email: sayap@gmail.com', '127.0.0.1', '2025-06-01 05:10:49'),
(92, 'sayap@gmail.com', 'User login dengan email: sayap@gmail.com', '127.0.0.1', '2025-06-01 05:11:26'),
(93, 'sayap@gmail.com', 'User logout dengan email: sayap@gmail.com', '127.0.0.1', '2025-06-01 05:11:38'),
(94, 'admin@gmail.com', 'User login dengan email: admin@gmail.com', '127.0.0.1', '2025-06-01 05:11:51'),
(95, 'admin@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-06-01 05:11:51'),
(96, 'admin@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 05:11:57'),
(97, 'admin@gmail.com', 'Admin melihat riwayat pembayaran.', '127.0.0.1', '2025-06-01 05:12:03'),
(98, 'admin@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-06-01 05:12:07'),
(99, 'admin@gmail.com', 'Melakukan booking untuk kendaraan: Toyota (sedan)', '127.0.0.1', '2025-06-01 05:13:52'),
(100, 'admin@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:14:05'),
(101, 'admin@gmail.com', 'User logout dengan email: admin@gmail.com', '127.0.0.1', '2025-06-01 05:14:12'),
(102, 'kudanil@gmail.com', 'User login dengan email: kudanil@gmail.com', '127.0.0.1', '2025-06-01 05:15:10'),
(103, 'kudanil@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:15:12'),
(104, 'kudanil@gmail.com', 'User logout dengan email: kudanil@gmail.com', '127.0.0.1', '2025-06-01 05:15:14'),
(105, 'sayap@gmail.com', 'User login dengan email: sayap@gmail.com', '127.0.0.1', '2025-06-01 05:15:57'),
(106, 'sayap@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:15:59'),
(107, 'sayap@gmail.com', 'Melakukan booking untuk kendaraan: Toyota (sedan)', '127.0.0.1', '2025-06-01 05:16:41'),
(108, 'sayap@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:16:44'),
(109, 'sayap@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 05:17:18'),
(110, 'sayap@gmail.com', 'Memperbarui status permintaan layanan ID: 11 menjadi: dikonfirmasi', '127.0.0.1', '2025-06-01 05:17:58'),
(111, 'sayap@gmail.com', 'Mengonfirmasi permintaan layanan ID: 11 dan menambahkan tagihan sebesar: 550000', '127.0.0.1', '2025-06-01 05:17:58'),
(112, 'sayap@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 05:17:58'),
(113, 'sayap@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:18:06'),
(114, 'sayap@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:18:17'),
(115, 'sayap@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:18:25'),
(116, 'sayap@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:19:21'),
(117, 'sayap@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:19:58'),
(118, 'sayap@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:20:13'),
(119, 'sayap@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:20:52'),
(120, 'sayap@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:21:18'),
(121, 'sayap@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:21:40'),
(122, 'sayap@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:21:51'),
(123, 'sayap@gmail.com', 'User logout dengan email: sayap@gmail.com', '127.0.0.1', '2025-06-01 05:21:55'),
(124, 'Guest', 'Mendaftarkan user baru dengan email: caca@gmail.com', '127.0.0.1', '2025-06-01 05:23:16'),
(125, 'caca@gmail.com', 'User login dengan email: caca@gmail.com', '127.0.0.1', '2025-06-01 05:23:41'),
(126, 'caca@gmail.com', 'User logout dengan email: caca@gmail.com', '127.0.0.1', '2025-06-01 05:23:55'),
(127, 'admin@gmail.com', 'User login dengan email: admin@gmail.com', '127.0.0.1', '2025-06-01 05:24:09'),
(128, 'admin@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-06-01 05:24:09'),
(129, 'admin@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 05:24:17'),
(130, 'admin@gmail.com', 'Admin melihat riwayat pembayaran.', '127.0.0.1', '2025-06-01 05:24:24'),
(131, 'admin@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-06-01 05:24:29'),
(132, 'admin@gmail.com', 'Melakukan booking untuk kendaraan: Honda (Sedan)', '127.0.0.1', '2025-06-01 05:25:55'),
(133, 'admin@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:26:08'),
(134, 'admin@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:26:10'),
(135, 'admin@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:26:10'),
(136, 'admin@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:26:11'),
(137, 'admin@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:26:11'),
(138, 'admin@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:26:13'),
(139, 'admin@gmail.com', 'User logout dengan email: admin@gmail.com', '127.0.0.1', '2025-06-01 05:26:21'),
(140, 'caca@gmail.com', 'User login dengan email: caca@gmail.com', '127.0.0.1', '2025-06-01 05:26:33'),
(141, 'caca@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:26:35'),
(142, 'caca@gmail.com', 'Melakukan booking untuk kendaraan: honda (sedan)', '127.0.0.1', '2025-06-01 05:27:18'),
(143, 'caca@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:27:20'),
(144, 'caca@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-06-01 05:28:01'),
(145, 'caca@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 05:28:04'),
(146, 'caca@gmail.com', 'Memperbarui status permintaan layanan ID: 13 menjadi: dikonfirmasi', '127.0.0.1', '2025-06-01 05:28:50'),
(147, 'caca@gmail.com', 'Mengonfirmasi permintaan layanan ID: 13 dan menambahkan tagihan sebesar: 360000', '127.0.0.1', '2025-06-01 05:28:50'),
(148, 'caca@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 05:28:50'),
(149, 'caca@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:28:57'),
(150, 'caca@gmail.com', 'Admin mengupload bukti pembayaran untuk registrasi ID: 13', '127.0.0.1', '2025-06-01 05:29:30'),
(151, 'caca@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:29:30'),
(152, 'caca@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 05:29:39'),
(153, 'caca@gmail.com', 'Admin memverifikasi pembayaran dengan ID: 9', '127.0.0.1', '2025-06-01 05:29:46'),
(154, 'caca@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 05:29:47'),
(155, 'caca@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:29:53'),
(156, 'caca@gmail.com', 'Memperbarui status permintaan layanan ID: 13 menjadi: selesai', '127.0.0.1', '2025-06-01 05:30:06'),
(157, 'caca@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 05:30:06'),
(158, 'caca@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:30:10'),
(159, 'caca@gmail.com', 'Melihat riwayat pembayaran.', '127.0.0.1', '2025-06-01 05:30:14'),
(160, 'caca@gmail.com', 'Melihat riwayat booking', '127.0.0.1', '2025-06-01 05:30:20'),
(161, 'caca@gmail.com', 'Melihat riwayat pembayaran.', '127.0.0.1', '2025-06-01 05:30:23'),
(162, 'caca@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 05:30:28'),
(163, 'caca@gmail.com', 'Mengakses halaman dashboard admin.', '127.0.0.1', '2025-06-01 05:30:30'),
(164, 'caca@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 05:30:45'),
(165, 'caca@gmail.com', 'Memperbarui status permintaan layanan ID: 13 menjadi: ditolak', '127.0.0.1', '2025-06-01 05:31:00'),
(166, 'caca@gmail.com', 'Menolak permintaan layanan ID: 13 dengan alasan: ga jelas', '127.0.0.1', '2025-06-01 05:31:00'),
(167, 'caca@gmail.com', 'Admin melihat permintaan layanan.', '127.0.0.1', '2025-06-01 05:31:00'),
(168, 'caca@gmail.com', 'Melihat riwayat pembayaran.', '127.0.0.1', '2025-06-01 05:31:03'),
(169, 'caca@gmail.com', 'Menambahkan kategori kendaraan baru dengan nama: dwadsad', '127.0.0.1', '2025-06-01 05:31:47'),
(170, 'caca@gmail.com', 'Memperbarui kategori kendaraan dengan nama: dwadsad', '127.0.0.1', '2025-06-01 05:32:01'),
(171, 'caca@gmail.com', 'Menghapus user dengan email: sapi@gmail.com', '127.0.0.1', '2025-06-01 05:32:48'),
(172, 'caca@gmail.com', 'Admin melihat riwayat pembayaran.', '127.0.0.1', '2025-06-01 05:32:52'),
(173, 'caca@gmail.com', 'Admin melihat riwayat pembayaran.', '127.0.0.1', '2025-06-01 05:33:00'),
(174, 'caca@gmail.com', 'Admin melihat riwayat pembayaran.', '127.0.0.1', '2025-06-01 05:34:02');

-- --------------------------------------------------------

--
-- Table structure for table `mekanik`
--

CREATE TABLE `mekanik` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `status` enum('aktif','tidak aktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mekanik`
--

INSERT INTO `mekanik` (`id`, `nama`, `telepon`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Evanny', '043242342', 'aktif', '2025-05-27 08:58:53', '2025-05-27 08:58:53'),
(2, 'Sim', '08325615221', 'aktif', '2025-05-28 00:22:56', '2025-05-28 00:22:56');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_27_064108_create_kategori_kendaraan_table', 1),
(5, '2025_05_27_064149_create_mekanik_table', 1),
(6, '2025_05_27_064249_create_layanan_table', 1),
(7, '2025_05_27_064346_create_service_requests_table', 1),
(8, '2025_05_27_064516_create_registrasi_table', 1),
(9, '2025_05_28_104910_create_log_activities_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permintaan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jumlah_tagihan` int(11) DEFAULT NULL,
  `rincian_biaya` text DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','diverifikasi','ditolak') NOT NULL DEFAULT 'menunggu',
  `tanggal_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_verifikasi` timestamp NULL DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `permintaan_id`, `jumlah_tagihan`, `rincian_biaya`, `bukti_pembayaran`, `status`, `tanggal_upload`, `tanggal_verifikasi`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 2, 323323, 'ban pecah berat', 'bukti_pembayaran/jdAMKkMi9ba5hHXjzyyZkK44nG65nzUXGgwVEHqC.jpg', 'diverifikasi', '2025-05-27 09:02:52', '2025-05-27 09:05:06', NULL, '2025-05-27 09:02:20', '2025-05-27 09:05:06'),
(2, 3, 456666, 'kerusakan parah pada mesin', 'bukti_pembayaran/54VZpJ5hS5cDVFeMOro5qyOVRyWvqMT7B2Og3UI3.jpg', 'ditolak', '2025-05-27 15:03:33', '2025-05-27 15:03:39', 'kurang jelas', '2025-05-27 15:02:54', '2025-05-27 15:03:55'),
(3, 5, 890000, 'kerusakan ringan pada mesin', 'bukti_pembayaran/6TUJ9EgnvQ33FVRMFwlCQHBbMFGLSjoaEuPXdyFf.jpg', 'diverifikasi', '2025-05-27 15:13:14', '2025-05-27 15:13:22', NULL, '2025-05-27 15:12:46', '2025-05-27 15:13:22'),
(4, 4, 555, 'ya', NULL, 'menunggu', '2025-05-28 01:24:24', NULL, NULL, '2025-05-28 01:24:24', '2025-05-28 01:24:24'),
(5, 6, 54353534, 'fefse', 'bukti_pembayaran/UVpMiKHG2PF5IQ9FYNddmbRwZoSH3byS18LQ4E9Q.jpg', 'diverifikasi', '2025-05-28 01:53:38', '2025-05-28 01:53:44', NULL, '2025-05-28 01:51:35', '2025-05-28 01:53:44'),
(6, 7, 650000, 'Mesin gear bawah diganti baru', NULL, 'menunggu', '2025-05-31 13:35:42', NULL, NULL, '2025-05-31 13:35:42', '2025-05-31 13:35:42'),
(7, 8, 780000, 'rusak dibagain mesin bawah', 'bukti_pembayaran/Fhvd2jHiygSWmLqsSkyPad1T9KvC0eqJfeDIU0US.jpg', 'menunggu', '2025-06-01 05:00:00', NULL, NULL, '2025-06-01 04:59:10', '2025-06-01 05:00:00'),
(8, 11, 550000, 'rusak parah dibagian gear', NULL, 'menunggu', '2025-06-01 05:17:58', NULL, NULL, '2025-06-01 05:17:58', '2025-06-01 05:17:58'),
(9, 13, 360000, 'remnya blong harus dikasih minyak lagi', 'bukti_pembayaran/efSFuWwpQPQhZmg3pNmRoaNy9ecRXc8EJGp7TXjb.jpg', 'ditolak', '2025-06-01 05:29:30', '2025-06-01 05:29:46', 'ga jelas', '2025-06-01 05:28:50', '2025-06-01 05:31:00');

-- --------------------------------------------------------

--
-- Table structure for table `registrasi`
--

CREATE TABLE `registrasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kategori_id` bigint(20) UNSIGNED DEFAULT NULL,
  `layanan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mekanik_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tipe_kendaraan` varchar(50) DEFAULT NULL,
  `nama_kendaraan` varchar(100) DEFAULT NULL,
  `model_kendaraan` varchar(100) DEFAULT NULL,
  `nama_pemilik` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `jenis_permintaan` enum('dropoff','pickup') DEFAULT NULL,
  `status` enum('menunggu','dikonfirmasi','diproses','selesai','ditolak') NOT NULL DEFAULT 'menunggu',
  `harga_ditentukan` decimal(10,2) DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `registrasi`
--

INSERT INTO `registrasi` (`id`, `user_id`, `kategori_id`, `layanan_id`, `mekanik_id`, `tipe_kendaraan`, `nama_kendaraan`, `model_kendaraan`, `nama_pemilik`, `no_hp`, `email`, `alamat`, `jenis_permintaan`, `status`, `harga_ditentukan`, `catatan_admin`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, 'sedan', 'Toyota', 'SUV', 'Lala', '084632423', NULL, 'Batam Centre', 'dropoff', 'menunggu', NULL, 'cepat ya', '2025-05-27 09:01:18', '2025-05-27 09:01:18'),
(2, 1, 1, 1, NULL, 'dwd', 'dwa', 'dwad', 'dw', 'ddw', NULL, 'dwd', 'dropoff', 'selesai', NULL, 'dw', '2025-05-27 09:02:07', '2025-05-27 09:05:11'),
(3, 3, 1, 2, NULL, 'dadaw', 'toyota', 'dwad', 'fff', '052452342', NULL, 'batam centre', 'dropoff', 'ditolak', NULL, 'sdadsdw', '2025-05-27 15:01:58', '2025-05-27 15:03:55'),
(4, 3, 1, 5, NULL, 'hgh', 'hhfghgf', 'ghhfh', 'dwad', 'd9989', NULL, 'cc', 'dropoff', 'dikonfirmasi', NULL, 'cccccc', '2025-05-27 15:07:34', '2025-05-28 01:24:24'),
(5, 3, 1, 2, NULL, 'sedan', 'toyota', 'bmw', 'tes', '084234234', NULL, 'kuala lumpur', 'dropoff', 'diproses', NULL, 'dibagian mesin gear paling bawah', '2025-05-27 15:11:46', '2025-05-27 15:13:22'),
(6, 1, 2, 1, NULL, 'fefs', 'toyota', 'fdssfsf', 'rawr', '08564564', NULL, 'fesfs', 'dropoff', 'selesai', NULL, 'fef', '2025-05-28 01:51:11', '2025-05-28 01:53:58'),
(7, 1, 3, 2, NULL, NULL, 'toyota', '456', 'RERE daada', '056564', NULL, 'rumah', 'dropoff', 'dikonfirmasi', NULL, 'dirumah', '2025-05-31 13:34:47', '2025-05-31 13:35:42'),
(8, 4, 1, 1, NULL, 'sedan', 'Toyota', 'SUV', 'SAPI', '07865646543', NULL, 'batam centre o 28', 'dropoff', 'dikonfirmasi', NULL, 'cepat ya', '2025-06-01 04:57:35', '2025-06-01 04:59:10'),
(9, 4, 1, 1, NULL, 'Sedan', 'Toyota', 'SUV', 'Dodo Simanjuntak', '04732472654', NULL, 'batam centre u 11', 'dropoff', 'menunggu', NULL, 'cepat ya buatnya', '2025-06-01 05:06:52', '2025-06-01 05:06:52'),
(10, 4, 1, 4, NULL, 'sedan', 'Toyota', 'SUV', 'butter', '08435264524', NULL, 'Batam Centre O 14', 'dropoff', 'menunggu', NULL, 'jangan lambat', '2025-06-01 05:13:52', '2025-06-01 05:13:52'),
(11, 8, 1, 4, NULL, 'sedan', 'Toyota', 'SUV', 'butter', '07865646543', NULL, 'Batam Centre O 14', 'dropoff', 'dikonfirmasi', NULL, 'jangan lambat ya', '2025-06-01 05:16:41', '2025-06-01 05:17:58'),
(12, 4, 1, 3, NULL, 'Sedan', 'Honda', 'SUV', 'Caca', '08316253231', NULL, 'Tiban Centre Blok A 13', 'pickup', 'menunggu', NULL, 'disamping pasar tiban centre', '2025-06-01 05:25:55', '2025-06-01 05:25:55'),
(13, 9, 1, 3, NULL, 'sedan', 'honda', 'SUV', 'Caca', '07865646543', NULL, 'Tiban Centre blok A 16', 'pickup', 'ditolak', NULL, 'dekat pasar tiban centre', '2025-06-01 05:27:18', '2025-06-01 05:31:00');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `owner_name` varchar(255) NOT NULL,
  `vehicle_name` varchar(255) NOT NULL,
  `vehicle_reg_no` varchar(255) NOT NULL,
  `assigned_to` bigint(20) UNSIGNED NOT NULL,
  `service` varchar(255) NOT NULL,
  `status` enum('pending','confirmed','on-progress','done','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('t8RfvZsjikbgc19BMooBCXs0DnJ5TqqstRx4FbZm', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMVN4OEpCOTJaSjBJaFJPTHlFNzJWQkdHZTl2UFdZNld1VUh5aFdaYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dfYWN0aXZpdHk/cGFnZT0zIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6OTt9', 1748756093);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pengguna','petugas') NOT NULL DEFAULT 'pengguna',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'pengguna', 'pengguna@gmail.com', '$2y$12$OoOWbW9WzB7GCldV/8ktJeli6YvmCOISrAC.WtBWWC.9agYLnkUxW', 'pengguna', '2025-05-27 08:00:09', '2025-05-27 08:00:09'),
(3, 'adminn', 'adminn@gmail.com', '$2y$12$rjaZ817kjG9WTjTuBrQymuweHWXVsZvd.lFghXwR43nN2u0Z3AhlG', 'admin', '2025-05-27 14:55:54', '2025-05-27 14:55:54'),
(4, 'admin', 'admin@gmail.com', '$2y$12$Hotx/VfslUv4YdHFUy.EVeY7JMPi1zPeq0nUKAHRi3zYUNk5ONSka', 'admin', '2025-05-28 01:07:34', '2025-05-28 01:07:34'),
(5, 'rpl', 'rpl@gmail.com', '$2y$12$CrmL7SaVbwdJ4q9u0vCmNOJ.x/.DzgmEj6oHq92EfjpGvkC/AGSau', 'pengguna', '2025-06-01 04:48:06', '2025-06-01 04:48:06'),
(7, 'kudanil', 'kudanil@gmail.com', '$2y$12$M/kgZyHjjKXuNF93HtPRTeT0FqCipjdY3XzxdGgn/rBRN/OnsPzLW', 'pengguna', '2025-06-01 05:03:55', '2025-06-01 05:03:55'),
(8, 'Sayap', 'sayap@gmail.com', '$2y$12$.zSuoCbvpvuPvQ9aFGBBveJMLs3rtXKnuS5YdhwGqFRgS/qmgrRjC', 'pengguna', '2025-06-01 05:10:49', '2025-06-01 05:10:49'),
(9, 'Caca', 'caca@gmail.com', '$2y$12$a3c0ty2fo.59K19mbH7KlOOgzj6RgC56FCKq7FlzA.2AuyGNPBuZy', 'pengguna', '2025-06-01 05:23:16', '2025-06-01 05:23:16');

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `kategori_kendaraan`
--
ALTER TABLE `kategori_kendaraan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_activities`
--
ALTER TABLE `log_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mekanik`
--
ALTER TABLE `mekanik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registrasi`
--
ALTER TABLE `registrasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registrasi_user_id_foreign` (`user_id`),
  ADD KEY `registrasi_kategori_id_foreign` (`kategori_id`),
  ADD KEY `registrasi_layanan_id_foreign` (`layanan_id`),
  ADD KEY `registrasi_mekanik_id_foreign` (`mekanik_id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_requests_assigned_to_foreign` (`assigned_to`);

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
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_kendaraan`
--
ALTER TABLE `kategori_kendaraan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `log_activities`
--
ALTER TABLE `log_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT for table `mekanik`
--
ALTER TABLE `mekanik`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `registrasi`
--
ALTER TABLE `registrasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `registrasi`
--
ALTER TABLE `registrasi`
  ADD CONSTRAINT `registrasi_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_kendaraan` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `registrasi_layanan_id_foreign` FOREIGN KEY (`layanan_id`) REFERENCES `layanan` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `registrasi_mekanik_id_foreign` FOREIGN KEY (`mekanik_id`) REFERENCES `mekanik` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `registrasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `mekanik` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
