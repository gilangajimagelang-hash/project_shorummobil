-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2025 at 07:42 AM
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
-- Database: `shorummobil`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_login`
--

CREATE TABLE `admin_login` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `login_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banner_iklan`
--

CREATE TABLE `banner_iklan` (
  `id` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `urutan` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banner_iklan`
--

INSERT INTO `banner_iklan` (`id`, `foto`, `judul`, `status`, `urutan`) VALUES
(1, 'OIP (1).jpg', 'beli sekarang gratis biaya admin!', 'aktif', 2),
(2, 'foto2.png', 'selamat datang di web kami!!', 'aktif', 1),
(3, 'logo.jpg', 'promo biasa sampai 10%.', 'aktif', 3);

-- --------------------------------------------------------

--
-- Table structure for table `foto_mobil`
--

CREATE TABLE `foto_mobil` (
  `id` int(11) NOT NULL,
  `mobil_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `foto_mobil`
--

INSERT INTO `foto_mobil` (`id`, `mobil_id`, `filename`) VALUES
(20, 18, '1764920753_326_OIP (7).jpg'),
(21, 18, '1764920859_768_OIP (8).jpg'),
(22, 19, '1764920926_600_OIP (5).jpg'),
(24, 21, '1764921530_683_WhatsApp Image 2025-12-05 at 14.55.48_78d4c421.jpg'),
(25, 21, '1764921569_416_WhatsApp Image 2025-12-05 at 14.54.57_dfbb5660.jpg'),
(26, 22, '1765018994_877_OIP (4).jpg'),
(27, 22, '1765022313_770_OIP (2).jpg'),
(28, 20, '1765022732_385_OIP (10).jpg'),
(29, 23, '1765109816_921_OIP (12).jpg'),
(30, 23, '1765109839_629_OIP (11).jpg'),
(31, 23, '1765109851_938_OIP (13).jpg');

-- --------------------------------------------------------

--
-- Table structure for table `mobil`
--

CREATE TABLE `mobil` (
  `id` int(11) NOT NULL,
  `nama_mobil` varchar(100) NOT NULL,
  `merk` varchar(50) NOT NULL,
  `tahun` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default.jpg',
  `status` enum('tersedia','terjual') DEFAULT 'tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `bahan_bakar` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mobil`
--

INSERT INTO `mobil` (`id`, `nama_mobil`, `merk`, `tahun`, `harga`, `deskripsi`, `foto`, `status`, `created_at`, `bahan_bakar`) VALUES
(18, 'pajero sport', 'mitsubishi', 2020, 460, '', '1764920753_691_OIP (6).jpg', 'tersedia', '2025-12-05 07:45:53', 'Diesel'),
(19, 'innova', 'toyota', 2021, 370, '', '1764920926_742_Mesin-Diesel-Toyota-Innova.jpg', 'terjual', '2025-12-05 07:48:46', 'Diesel'),
(20, 'avanza', 'mitsubishi', 2017, 170, 'kilometer rendah, baarang jojis', '1764921197_744_OIP (9).jpg', 'terjual', '2025-12-05 07:53:17', 'Bensin'),
(21, 'l 300', 'mitsubishi', 2018, 145, '', '1764921530_594_WhatsApp Image 2025-12-05 at 14.55.46_6b627fa1.jpg', 'terjual', '2025-12-05 07:58:50', 'Diesel'),
(22, 'innova', 'mitsubishi', 2021, 450, 'barang josjis', '1765018994_751_OIP (3).jpg', 'tersedia', '2025-12-06 11:03:14', 'Diesel'),
(23, 'grandmax', 'Daihatsu', 2017, 85, 'bekas pemakaian pribadi. !! TIDAK BEKAS MOBIL PT ATAUPUN TRAVEL !!', '1765109816_795_OIP.jpg', 'tersedia', '2025-12-07 12:16:56', 'Bensin');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'gilang', 'gilangajimagelang@gmail.com', '$2y$10$94k7XmWih94wjZzW4M4XLuP9pbvwDVomXcKWdWpzt.IsJ2rLJeAey', '2025-12-04 07:24:39'),
(5, 'ika', 'ikanopita5@gmail.com', '$2y$10$/6BFs/N42woo7xIzg7my9O6ajPb1990jUSQ/xalIA4YlcZEbKS06u', '2025-12-04 15:09:19'),
(6, 'nabil', 'nabil@gmail', '$2y$10$2S/XxIILhA.8iICBJjmVnude912C5lHSafumpU.aRbONzO1PunFiC', '2025-12-04 15:14:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_login`
--
ALTER TABLE `admin_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner_iklan`
--
ALTER TABLE `banner_iklan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `foto_mobil`
--
ALTER TABLE `foto_mobil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mobil_id` (`mobil_id`);

--
-- Indexes for table `mobil`
--
ALTER TABLE `mobil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_login`
--
ALTER TABLE `admin_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banner_iklan`
--
ALTER TABLE `banner_iklan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `foto_mobil`
--
ALTER TABLE `foto_mobil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `mobil`
--
ALTER TABLE `mobil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `foto_mobil`
--
ALTER TABLE `foto_mobil`
  ADD CONSTRAINT `foto_mobil_ibfk_1` FOREIGN KEY (`mobil_id`) REFERENCES `mobil` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
