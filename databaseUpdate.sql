
-- ============================================================
-- KosBooking Pro — Full Database Schema + Seed
-- Engine: InnoDB | Charset: utf8mb4
-- ============================================================

CREATE DATABASE IF NOT EXISTS kosbooking
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE kosbooking;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ============================================================
-- 1. USERS
-- ============================================================

CREATE TABLE users (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    username        VARCHAR(50) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    role            ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. ROOMS
-- ============================================================

CREATE TABLE rooms (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(100) NOT NULL,
    description         TEXT,
    facilities          TEXT,
    price_per_month     DECIMAL(12,2) NOT NULL,
    status              ENUM('Available','Maintenance')
                        NOT NULL DEFAULT 'Available',
    image_path          VARCHAR(255),
    created_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. TRANSACTIONS
-- ============================================================

CREATE TABLE transactions (
    id                      INT AUTO_INCREMENT PRIMARY KEY,
    user_id                 INT NOT NULL,
    room_id                 INT NOT NULL,
    duration_months         INT NOT NULL,
    deposit                 DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    total_amount            DECIMAL(12,2) NOT NULL,
    status                  ENUM(
                                'DRAFT',
                                'PENDING_PAYMENT',
                                'WAITING_VALIDATION',
                                'ACTIVE',
                                'CANCELLED'
                              ) NOT NULL DEFAULT 'DRAFT',
    payment_proof           VARCHAR(255) DEFAULT NULL,
    created_at              TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at              TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_transactions_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_transactions_room
        FOREIGN KEY (room_id)
        REFERENCES rooms(id)
        ON DELETE CASCADE,

    INDEX idx_transactions_room_status (room_id, status, expires_at),
    INDEX idx_transactions_user (user_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. CART
-- ============================================================

CREATE TABLE cart (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    user_id         INT NOT NULL,
    room_id         INT NOT NULL,
    quantity        INT NOT NULL DEFAULT 1,
    created_at      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_cart_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_cart_room
        FOREIGN KEY (room_id)
        REFERENCES rooms(id)
        ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. ROOM REVIEWS
-- ============================================================

CREATE TABLE room_reviews (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    room_id             INT NOT NULL,
    user_id             INT NOT NULL,
    transaction_id      INT NOT NULL,
    rating              INT NOT NULL,
    comment             TEXT,
    created_at          TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_review_room
        FOREIGN KEY (room_id)
        REFERENCES rooms(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_review_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
'2026-05-26 11:57:04');

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

