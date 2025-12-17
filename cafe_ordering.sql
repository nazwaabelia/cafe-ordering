-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2025 at 03:20 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cafe_ordering`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama_lengkap`, `created_at`) VALUES
(1, 'admin', '$2y$10$o3dsjrl7DK4yqvv4Lz.S8OKmXJSGE/SZO7I5Fo20pMQp3UbtHdhae', 'Administrator Kafe', '2025-10-22 20:08:14');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `kategori` enum('teh','cookies') NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `gambar` varchar(255) DEFAULT 'default.jpg',
  `status` enum('tersedia','habis') DEFAULT 'tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `kategori`, `harga`, `stok`, `gambar`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Teh Tarik Original', 'teh', 12000.00, 30, 'teh-tarik.jpg', 'tersedia', '2025-10-22 20:08:04', '2025-12-04 20:02:02'),
(2, 'Teh Hijau', 'teh', 10000.00, 27, 'teh-hijau.jpg', 'tersedia', '2025-10-22 20:08:04', '2025-12-04 20:02:02'),
(3, 'Teh Lemon', 'teh', 13000.00, 30, 'teh-lemon.jpg', 'tersedia', '2025-10-22 20:08:04', '2025-12-04 20:02:02'),
(4, 'Thai Tea', 'teh', 15000.00, 18, 'thai-tea.jpg', 'tersedia', '2025-10-22 20:08:04', '2025-12-04 20:02:02'),
(5, 'Matcha Latte', 'teh', 18000.00, 15, 'matcha-latte.jpg', 'tersedia', '2025-10-22 20:08:04', '2025-12-04 20:02:02'),
(6, 'Chocolate Chip Cookies', 'cookies', 8000.00, 40, 'choco-chip.jpg', 'tersedia', '2025-10-22 20:08:04', '2025-12-04 20:02:02'),
(7, 'Oatmeal Raisin Cookies', 'cookies', 9000.00, 35, 'oatmeal.jpg', 'tersedia', '2025-10-22 20:08:04', '2025-12-04 20:02:02'),
(8, 'Red Velvet Cookies', 'cookies', 10000.00, 30, 'red-velvet.jpg', 'tersedia', '2025-10-22 20:08:04', '2025-12-04 20:02:02'),
(9, 'Peanut Butter Cookies', 'cookies', 8500.00, 25, 'peanut-butter.jpg', 'tersedia', '2025-10-22 20:08:04', '2025-12-04 20:02:02'),
(10, 'Double Chocolate Cookies', 'cookies', 11000.00, 20, 'double-choco.jpg', 'tersedia', '2025-10-22 20:08:04', '2025-12-04 20:02:02');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `nomor_meja` varchar(50) NOT NULL,
  `tipe_pesanan` enum('dine-in','take-away') NOT NULL,
  `daftar_item` text NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status` enum('menunggu','diterima','disiapkan','siap','selesai') DEFAULT 'menunggu',
  `waktu_pesan` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `nama_pelanggan`, `nomor_meja`, `tipe_pesanan`, `daftar_item`, `total_harga`, `status`, `waktu_pesan`, `updated_at`) VALUES
(1, 'Hadi', 'Meja 5', 'dine-in', '[{\"id\":4,\"name\":\"Thai Tea\",\"price\":15000,\"qty\":1}]', 15000.00, 'selesai', '2025-12-03 06:55:52', '2025-12-03 07:17:52'),
(2, 'Ima', 'meja 10', 'take-away', '[{\"id\":3,\"name\":\"Teh Lemon\",\"price\":13000,\"qty\":1},{\"id\":2,\"name\":\"Teh Hijau\",\"price\":10000,\"qty\":1}]', 23000.00, 'selesai', '2025-12-03 07:19:00', '2025-12-03 07:19:28'),
(3, 'Sunny', 'Meja 6', 'dine-in', '[{\"id\":3,\"name\":\"Teh Lemon\",\"price\":13000,\"qty\":24}]', 312000.00, 'selesai', '2025-12-03 07:20:07', '2025-12-03 07:20:46'),
(4, 'Hanifah', 'meja 7', 'dine-in', '[{\"id\":2,\"name\":\"Teh Hijau\",\"price\":10000,\"qty\":1},{\"id\":4,\"name\":\"Thai Tea\",\"price\":15000,\"qty\":1}]', 25000.00, 'selesai', '2025-12-03 07:42:43', '2025-12-03 07:46:53'),
(6, 'Han', 'meja 10', 'dine-in', '[{\"id\":2,\"name\":\"Teh Hijau\",\"price\":10000,\"qty\":1}]', 10000.00, 'menunggu', '2025-12-04 11:29:34', '2025-12-10 23:50:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
