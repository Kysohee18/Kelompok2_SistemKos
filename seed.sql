-- ============================================================
-- KosBooking Pro — Seed Data
-- Safe to run multiple times (idempotent)
-- ============================================================

USE kosbooking;

-- Test user (password: user123)
INSERT IGNORE INTO users (username, password_hash, role)
VALUES ('user', '$2y$10$HKZ1rB8MVmGHiIZSF6KXoOqLblFPNMnE4uFEx7YOGF4GJFOlF.YQe', 'user');

-- Sample rooms (REPLACE = idempotent, updates if exists)
REPLACE INTO rooms (id, name, description, facilities, price_per_month, status, image_path) VALUES
(1, 'Kost Elite Premium Diponegoro',
 'Kost eksklusif di pusat kota dengan akses 24 jam dan keamanan CCTV. Cocok untuk mahasiswa dan profesional.',
 'K. Mandi Dalam - WiFi 50Mbps - AC - TV Kabel - Lemari - Meja Kerja - CCTV - Laundry - Listrik Token - Air Panas',
 1750000.00, 'Available', 'uploads/rooms/kost_elite.jpg'),

(2, 'Kost Putri Amanah Sukoharjo',
 'Lingkungan asri dan aman khusus putri. Dekat dengan universitas dan pusat perbelanjaan.',
 'K. Mandi Luar - WiFi 30Mbps - Lemari - Meja Belajar - Dapur Bersama - Parkir Motor - CCTV - Listrik Termasuk',
 950000.00, 'Available', 'uploads/rooms/kost_putri.jpg'),

(3, 'Kost Mewah Khatulistiwa',
 'Kost modern dengan desain minimalis. Dilengkapi smart lock dan akses kartu.',
 'K. Mandi Dalam - WiFi 100Mbps - AC Smart - Smart TV - Kulkas - Dispenser - Lemari Besar - Meja Kerja - Sofa - Parkir Mobil - GYM - Rooftop',
 2500000.00, 'Available', 'uploads/rooms/kost_mewah.jpg'),

(4, 'Kost Campur NYaman',
 'Kost campuran yang nyaman dengan suasana kekeluargaan. Free laundry 2x seminggu.',
 'K. Mandi Dalam - WiFi 20Mbps - AC - Lemari - Meja Belajar - Dapur - Parkir Motor - Laundry Gratis - Listrik Termasuk',
 1200000.00, 'Available', 'uploads/rooms/kost_nyaman.jpg'),

(5, 'Kost Putra Harmoni',
 'Kost khusus putra dengan lingkungan yang bersih dan teratur. Dekat kampus UNS.',
 'K. Mandi Luar - WiFi - Kipas Angin - Lemari - Meja - Dapur - Parkir Motor - Listrik Termasuk - Air Sumur',
 650000.00, 'Available', 'uploads/rooms/kost_harmoni.jpg'),

(6, 'Kost Premium Solo Baru',
 'Kost premium di kawasan Solo Baru. Strategis dekat dengan pusat bisnis dan kuliner.',
 'K. Mandi Dalam - WiFi 50Mbps - AC - Lemari - Meja Kerja - Dispenser - Parkir Mobil - CCTV - Listrik Token - Air PDAM',
 1850000.00, 'Available', 'uploads/rooms/kost_premium.jpg'),

(7, 'Kost Sederhana Mulya',
 'Kost ekonomis dengan fasilitas lengkap. Cocok untuk karyawan dan mahasiswa budget.',
 'K. Mandi Luar - WiFi 10Mbps - Kipas - Lemari - Dapur Bersama - Parkir Motor - Listrik Termasuk',
 500000.00, 'Available', 'uploads/rooms/kost_sederhana.jpg'),

(8, 'Kost Eksklusif Purwosari',
 'Butik kost dengan desain interior Eropa. Layanan housekeeping 2x seminggu.',
 'K. Mandi Dalam - WiFi 100Mbps - AC - Smart TV - Kulkas - Microwave - Water Heater - Lemari Walk-in - Meja Kerja - Sofa Bed - Parkir Mobil - Housekeeping - Laundry - GYM - Kolam Renang',
 3500000.00, 'Available', 'uploads/rooms/kost_eksklusif.jpg');
