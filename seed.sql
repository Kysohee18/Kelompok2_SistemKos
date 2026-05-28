-- ============================================================
-- KosBooking Pro — Seed Data
-- Safe to run multiple times (idempotent)
-- ============================================================

USE kosbooking;

 -- ============================================================
-- SEED USERS
-- ============================================================

INSERT INTO users (id, username, password_hash, role, created_at) VALUES
(1, 'admin', '$2y$10$3fB3q4htfXT92Zerbtclv.r39PFdubR2y4fYLHo2loGA5MwxNUHja', 'admin', '2026-05-26 11:56:38'),
(2, 'user', '$2y$10$6UOeqHheAtkx.tS/Yvt9nut4th83LInayaUHeJDYG8bj/rLo.IIL6', 'user', '2026-05-26 11:57:04'),
(3, 'riza', '$2y$10$OjEKJRftiGST1DE23/rAbeyfiYaIJKPKHDYjYxzYOgZCJpKE85.FW', 'user', '2026-05-27 10:06:11');

-- ============================================================
-- SEED ROOMS
-- ============================================================

INSERT INTO rooms
(id, name, description, facilities, price_per_month, status, image_path, created_at)
VALUES

(1, 'Kost Elite Premium Diponegoro',
'Kost eksklusif di pusat kota dengan akses 24 jam dan keamanan CCTV.',
'K. Mandi Dalam - WiFi 50Mbps - AC - CCTV',
1750000.00,
'Available',
'uploads/rooms/kostElite.jpg',
'2026-05-26 11:57:04'),

(2, 'Kost Putri Amanah Sukoharjo',
'Lingkungan asri dan aman khusus putri.',
'WiFi - Lemari - Dapur Bersama',
950000.00,
'Available',
'uploads/rooms/kostPutriAmanah.jpg',
'2026-05-26 11:57:04'),

(3, 'Kost Mewah Khatulistiwa',
'Kost modern dengan desain minimalis.',
'WiFi 100Mbps - Smart TV - Rooftop',
2500000.00,
'Available',
'uploads/rooms/kostMewah.webp',
'2026-05-26 11:57:04'),

(4, 'Kost Campur Nyaman',
 'Kost campuran yang nyaman dengan suasana kekeluargaan. Free laundry 2x seminggu.',
 'K. Mandi Dalam - WiFi 20Mbps - AC - Lemari - Meja Belajar - Dapur - Parkir Motor - Laundry Gratis - Listrik Termasuk',
 1200000.00, 
 'Available', 
 'uploads/rooms/kostCampur.jpeg',
 '2026-05-26 11:57:04'),

(5, 'Kost Putra Harmoni',
 'Kost khusus putra dengan lingkungan yang bersih dan teratur. Dekat kampus UNS.',
 'K. Mandi Luar - WiFi - Kipas Angin - Lemari - Meja - Dapur - Parkir Motor - Listrik Termasuk - Air Sumur',
 650000.00, 'Available', 'uploads/rooms/kostPutra.jpg',
 '2026-05-26 11:57:04'),

(6, 'Kost Premium Solo Baru',
 'Kost premium di kawasan Solo Baru. Strategis dekat dengan pusat bisnis dan kuliner.',
 'K. Mandi Dalam - WiFi 50Mbps - AC - Lemari - Meja Kerja - Dispenser - Parkir Mobil - CCTV - Listrik Token - Air PDAM',
 1850000.00, 'Available', 'uploads/rooms/kostPremium.webp', '2026-05-26 11:57:04'),

(7, 'Kost Sederhana Mulya',
 'Kost ekonomis dengan fasilitas lengkap. Cocok untuk karyawan dan mahasiswa budget.',
 'K. Mandi Luar - WiFi 10Mbps - Kipas - Lemari - Dapur Bersama - Parkir Motor - Listrik Termasuk',
 500000.00, 'Available', 'uploads/rooms/kostSederhana.jpg', '2026-05-26 11:57:04'),

(8, 'Kost Eksklusif Purwosari',
 'Butik kost dengan desain interior Eropa. Layanan housekeeping 2x seminggu.',
 'K. Mandi Dalam - WiFi 100Mbps - AC - Smart TV - Kulkas - Microwave - Water Heater - Lemari Walk-in - Meja Kerja - Sofa Bed - Parkir Mobil - Housekeeping - Laundry - GYM - Kolam Renang',
 3500000.00, 'Available', 'uploads/rooms/kostEks.jpg', '2026-05-26 11:57:04');

-- ============================================================
-- SEED TRANSACTIONS
-- ============================================================

INSERT INTO transactions
(id, user_id, room_id, duration_months, deposit, total_amount, status, payment_proof, created_at, expires_at)
VALUES

(3, 1, 1, 1, 875000.00, 1750000.00,
'CANCELLED',
NULL,
'2026-05-27 07:43:22',
'2026-05-27 07:58:22'),

(4, 2, 1, 1, 875000.00, 1750000.00,
'ACTIVE',
'uploads/payments/payment_4_1779876235.jpg',
'2026-05-27 10:03:35',
NULL);

-- ============================================================
-- SEED CART
-- ============================================================

INSERT INTO cart
(id, user_id, room_id, created_at, quantity)
VALUES
(6, 1, 3, '2026-05-27 22:57:16', 1);

-- ============================================================
-- SEED REVIEWS
-- ============================================================

INSERT INTO room_reviews
(id, room_id, user_id, transaction_id, rating, comment, created_at)
VALUES

(1, 2, 3, 5, 5,
'Bagus banget...',
'2026-05-27 15:11:28'),

(2, 1, 2, 4, 5,
'Mantap... harga juga lumayan...',
'2026-05-27 16:51:08');

COMMIT;


