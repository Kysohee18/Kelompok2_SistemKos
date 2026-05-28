```sql
-- ============================================================
-- KosBooking Pro — Database Schema (website_ready)
-- Engine: InnoDB | Charset: utf8mb4
-- ============================================================

CREATE DATABASE IF NOT EXISTS kosbooking
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE kosbooking;

-- -----------------------------------------------------------
-- 1. users
-- -----------------------------------------------------------
CREATE TABLE users (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    username        VARCHAR(50) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    role            ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 2. rooms
-- -----------------------------------s------------------------
CREATE TABLE rooms (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    name              VARCHAR(100) NOT NULL,
    description       TEXT,
    facilities        TEXT,
    price_per_month   DECIMAL(12,2) NOT NULL,
    status            ENUM('Available', 'Maintenance')
                      NOT NULL DEFAULT 'Available',
    image_path        VARCHAR(255),
    created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 3. transactions
-- -----------------------------------------------------------
CREATE TABLE transactions (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    user_id             INT NOT NULL,
    room_id             INT NOT NULL,
    duration_months     INT NOT NULL,
    deposit             DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    total_amount        DECIMAL(12,2) NOT NULL,
    status              ENUM(
                            'DRAFT',
                            'PENDING_PAYMENT',
                            'WAITING_VALIDATION',
                            'ACTIVE',
                            'CANCELLED'
                        ) NOT NULL DEFAULT 'DRAFT',
    payment_proof       VARCHAR(255) DEFAULT NULL,
    created_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at          TIMESTAMP NULL DEFAULT NULL,

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

-- -----------------------------------------------------------
-- 4. cart
-- -----------------------------------------------------------
CREATE TABLE cart (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT NOT NULL,
    room_id       INT NOT NULL,
    quantity      INT NOT NULL DEFAULT 1,
    created_at    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_cart_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_cart_room
        FOREIGN KEY (room_id)
        REFERENCES rooms(id)
        ON DELETE CASCADE,

    INDEX idx_cart_user (user_id),
    INDEX idx_cart_room (room_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 5. room_reviews
-- -----------------------------------------------------------
CREATE TABLE room_reviews (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    room_id           INT NOT NULL,
    user_id           INT NOT NULL,
    transaction_id    INT NOT NULL,
    rating            INT NOT NULL,
    comment           TEXT,
    created_at        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_reviews_room
        FOREIGN KEY (room_id)
        REFERENCES rooms(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_reviews_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    INDEX idx_reviews_room (room_id),
    INDEX idx_reviews_user (user_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 6. Seed Users
-- -----------------------------------------------------------
INSERT INTO users (username, password_hash, role)
VALUES
(
    'admin',
    '$2y$10$3fB3q4htfXT92Zerbtclv.r39PFdubR2y4fYLHo2loGA5MwxNUHja',
    'admin'
),
(
    'user',
    '$2y$10$6UOeqHheAtkx.tS/Yvt9nut4th83LInayaUHeJDYG8bj/rLo.IIL6',
    'user'
),
(
    'riza',
    '$2y$10$OjEKJRftiGST1DE23/rAbeyfiYaIJKPKHDYjYxzYOgZCJpKE85.FW',
    'user'
);

-- -----------------------------------------------------------
-- 7. Seed Rooms
-- -----------------------------------------------------------
INSERT INTO rooms
(name, description, facilities, price_per_month, status, image_path)
VALUES
(
    'Kost Elite Premium Diponegoro',
    'Kost eksklusif di pusat kota dengan akses 24 jam dan keamanan CCTV.',
    'K. Mandi Dalam - WiFi 50Mbps - AC - TV Kabel - CCTV',
    1750000.00,
    'Available',
    'uploads/rooms/kostElite.jpg'
),
(
    'Kost Putri Amanah Sukoharjo',
    'Lingkungan asri dan aman khusus putri.',
    'WiFi - Lemari - Dapur Bersama - CCTV',
    950000.00,
    'Available',
    'uploads/rooms/kostPutriAmanah.jpg'
),
(
    'Kost Mewah Khatulistiwa',
    'Kost modern dengan desain minimalis.',
    'WiFi 100Mbps - AC Smart - Smart TV - GYM',
    2500000.00,
    'Available',
    'uploads/rooms/kostMewah.webp'
);

-- -----------------------------------------------------------
-- 8. Seed Transactions
-- -----------------------------------------------------------
INSERT INTO transactions
(user_id, room_id, duration_months, deposit, total_amount, status)
VALUES
(2, 1, 1, 875000.00, 1750000.00, 'ACTIVE'),
(3, 2, 1, 475000.00, 950000.00, 'ACTIVE');

-- -----------------------------------------------------------
-- 9. Seed Reviews
-- -----------------------------------------------------------
INSERT INTO room_reviews
(room_id, user_id, transaction_id, rating, comment)
VALUES
(2, 3, 5, 5, 'Bagus banget...'),
(1, 2, 4, 5, 'Mantap... harga juga lumayan...');

-- -----------------------------------------------------------
-- 10. Seed Cart
-- -----------------------------------------------------------
INSERT INTO cart
(user_id, room_id, quantity)
VALUES
(1, 3, 1);
```
