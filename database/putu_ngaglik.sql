-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2025 at 12:37 PM
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
-- Database: `putu_ngaglik`
--

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `bestseller` tinyint(1) DEFAULT 0,
  `unik` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama`, `harga`, `gambar`, `deskripsi`, `bestseller`, `unik`, `created_at`) VALUES
(1, 'Kue Putu', 15000, 'putu.png', 'Kue putu bambu isi gula merah dengan kelapa parut', 1, 0, '2025-12-03 16:35:02'),
(2, 'Ketan Bubuk', 7000, 'ketan.png', 'Terbuat dari beras ketan putih yang dikukus hingga pulen, kemudian disajikan dengan taburan bubuk kedelai yang gurih dan sedikit manis', 0, 0, '2025-12-03 16:35:02'),
(3, 'Klepon', 7000, 'klepon.png', 'Klepon isi gula merah lumer', 0, 1, '2025-12-03 16:35:02'),
(4, 'Klanting', 7000, 'klanting.png', 'Klanting merupakan makanan ringan tradisional khas Jawa yang terbuat dari tepung kanji atau juga dikenal tapioka, berwarna-warni', 0, 0, '2025-12-03 18:09:12'),
(5, 'Lupis Ketan', 7000, 'lupis.png', 'Lupis terbuat dari beras ketan yang dimasak, lalu dibungkus dengan daun pisang', 0, 0, '2025-12-03 18:09:47'),
(6, 'Nagasari', 7000, 'nagasari2.jpeg', 'kue basah yang dibuat dari tepung beras, sagu, santan, dan gula yang nantinya dibungkus dengan daun pisang', 0, 0, '2025-12-03 18:10:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` enum('admin','pelanggan') DEFAULT 'pelanggan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `role`) VALUES
(1, 'admin', 'putu2025', 'Admin Putu Ngaglik', 'admin'),
(2, 'ivan', 'putu2025', 'Ivan Sawadldudyansyah', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
