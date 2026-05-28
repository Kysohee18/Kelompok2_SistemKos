-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 28, 2026 at 10:01 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kosbooking`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `room_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `quantity` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `room_id`, `created_at`, `quantity`) VALUES
(6, 1, 3, '2026-05-27 22:57:16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `facilities` text COLLATE utf8mb4_unicode_ci,
  `price_per_month` decimal(12,2) NOT NULL,
  `status` enum('Available','Maintenance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Available',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `description`, `facilities`, `price_per_month`, `status`, `image_path`, `created_at`) VALUES
(1, 'Kost Elite Premium Diponegoro', 'Kost eksklusif di pusat kota dengan akses 24 jam dan keamanan CCTV. Cocok untuk mahasiswa dan profesional.', 'K. Mandi Dalam - WiFi 50Mbps - AC - TV Kabel - Lemari - Meja Kerja - CCTV - Laundry - Listrik Token - Air Panas', 1750000.00, 'Available', 'uploads/rooms/kostElite.jpg', '2026-05-26 11:57:04'),
(2, 'Kost Putri Amanah Sukoharjo', 'Lingkungan asri dan aman khusus putri. Dekat dengan universitas dan pusat perbelanjaan.', 'K. Mandi Luar - WiFi 30Mbps - Lemari - Meja Belajar - Dapur Bersama - Parkir Motor - CCTV - Listrik Termasuk', 950000.00, 'Available', 'uploads/rooms/kostPutriAmanah.jpg', '2026-05-26 11:57:04'),
(3, 'Kost Mewah Khatulistiwa', 'Kost modern dengan desain minimalis. Dilengkapi smart lock dan akses kartu.', 'K. Mandi Dalam - WiFi 100Mbps - AC Smart - Smart TV - Kulkas - Dispenser - Lemari Besar - Meja Kerja - Sofa - Parkir Mobil - GYM - Rooftop', 2500000.00, 'Available', 'uploads/rooms/kostMewah.webp', '2026-05-26 11:57:04'),
(4, 'Kost Campur Nyaman', 'Kost campuran yang nyaman dengan suasana kekeluargaan. Free laundry 2x seminggu.', 'K. Mandi Dalam - WiFi 20Mbps - AC - Lemari - Meja Belajar - Dapur - Parkir Motor - Laundry Gratis - Listrik Termasuk', 1200000.00, 'Available', 'uploads/rooms/kostCampur.jpeg', '2026-05-26 11:57:04'),
(5, 'Kost Putra Harmoni', 'Kost khusus putra dengan lingkungan yang bersih dan teratur. Dekat kampus UNS.', 'K. Mandi Luar - WiFi - Kipas Angin - Lemari - Meja - Dapur - Parkir Motor - Listrik Termasuk - Air Sumur', 650000.00, 'Available', 'uploads/rooms/kostPutra.jpg', '2026-05-26 11:57:04'),
(6, 'Kost Premium Solo Baru', 'Kost premium di kawasan Solo Baru. Strategis dekat dengan pusat bisnis dan kuliner.', 'K. Mandi Dalam - WiFi 50Mbps - AC - Lemari - Meja Kerja - Dispenser - Parkir Mobil - CCTV - Listrik Token - Air PDAM', 1850000.00, 'Available', 'uploads/rooms/kostPremium.webp', '2026-05-26 11:57:04'),
(7, 'Kost Sederhana Mulya', 'Kost ekonomis dengan fasilitas lengkap. Cocok untuk karyawan dan mahasiswa budget.', 'K. Mandi Luar - WiFi 10Mbps - Kipas - Lemari - Dapur Bersama - Parkir Motor - Listrik Termasuk', 500000.00, 'Available', 'uploads/rooms/kostSederhana.jpg', '2026-05-26 11:57:04'),
(8, 'Kost Eksklusif Purwosari', 'Butik kost dengan desain interior Eropa. Layanan housekeeping 2x seminggu.', 'K. Mandi Dalam - WiFi 100Mbps - AC - Smart TV - Kulkas - Microwave - Water Heater - Lemari Walk-in - Meja Kerja - Sofa Bed - Parkir Mobil - Housekeeping - Laundry - GYM - Kolam Renang', 3500000.00, 'Available', 'uploads/rooms/kostEks.jpg', '2026-05-26 11:57:04');

-- --------------------------------------------------------

--
-- Table structure for table `room_reviews`
--

CREATE TABLE `room_reviews` (
  `id` int NOT NULL,
  `room_id` int NOT NULL,
  `user_id` int NOT NULL,
  `transaction_id` int NOT NULL,
  `rating` int NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `room_reviews`
--

INSERT INTO `room_reviews` (`id`, `room_id`, `user_id`, `transaction_id`, `rating`, `comment`, `created_at`) VALUES
(1, 2, 3, 5, 5, 'Bagus banget...', '2026-05-27 15:11:28'),
(2, 1, 2, 4, 5, 'Mantap...harga juga lumayan...', '2026-05-27 16:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `room_id` int NOT NULL,
  `duration_months` int NOT NULL,
  `deposit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL,
  `status` enum('DRAFT','PENDING_PAYMENT','WAITING_VALIDATION','ACTIVE','CANCELLED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `payment_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `room_id`, `duration_months`, `deposit`, `total_amount`, `status`, `payment_proof`, `created_at`, `expires_at`) VALUES
(3, 1, 1, 1, 875000.00, 1750000.00, 'CANCELLED', NULL, '2026-05-27 07:43:22', '2026-05-27 07:58:22'),
(4, 2, 1, 1, 875000.00, 1750000.00, 'ACTIVE', 'uploads/payments/payment_4_1779876235.jpg', '2026-05-27 10:03:35', NULL),
(5, 3, 2, 1, 475000.00, 950000.00, 'ACTIVE', 'uploads/payments/payment_5_1779878028.jpg', '2026-05-27 10:33:02', NULL),
(6, 2, 3, 1, 1250000.00, 2500000.00, 'CANCELLED', NULL, '2026-05-27 17:31:47', '2026-05-27 17:46:47'),
(7, 2, 4, 2, 1200000.00, 2400000.00, 'CANCELLED', NULL, '2026-05-27 17:34:24', '2026-05-27 17:49:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$3fB3q4htfXT92Zerbtclv.r39PFdubR2y4fYLHo2loGA5MwxNUHja', 'admin', '2026-05-26 11:56:38'),
(2, 'user', '$2y$10$6UOeqHheAtkx.tS/Yvt9nut4th83LInayaUHeJDYG8bj/rLo.IIL6', 'user', '2026-05-26 11:57:04'),
(3, 'riza', '$2y$10$OjEKJRftiGST1DE23/rAbeyfiYaIJKPKHDYjYxzYOgZCJpKE85.FW', 'user', '2026-05-27 10:06:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_reviews`
--
ALTER TABLE `room_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_transactions_room_status` (`room_id`,`status`,`expires_at`),
  ADD KEY `idx_transactions_user` (`user_id`);

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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `room_reviews`
--
ALTER TABLE `room_reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_reviews`
--
ALTER TABLE `room_reviews`
  ADD CONSTRAINT `room_reviews_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_transactions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
