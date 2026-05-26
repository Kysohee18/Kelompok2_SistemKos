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
    id            INT           AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50)   NOT NULL UNIQUE,
    password_hash VARCHAR(255)  NOT NULL,
    role          ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 2. rooms
-- -----------------------------------------------------------
CREATE TABLE rooms (
    id              INT             AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100)    NOT NULL,
    description     TEXT,
    facilities      TEXT,
    price_per_month DECIMAL(12,2)   NOT NULL,
    status          ENUM('Available', 'Maintenance') NOT NULL DEFAULT 'Available',
    image_path      VARCHAR(255),
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 3. transactions  (State Machine)
-- -----------------------------------------------------------
CREATE TABLE transactions (
    id                INT             AUTO_INCREMENT PRIMARY KEY,
    user_id           INT             NOT NULL,
    room_id           INT             NOT NULL,
    duration_months   INT             NOT NULL,
    deposit           DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    total_amount      DECIMAL(12,2)   NOT NULL,
    status            ENUM('DRAFT','PENDING_PAYMENT','WAITING_VALIDATION','ACTIVE','CANCELLED')
                                      NOT NULL DEFAULT 'DRAFT',
    payment_proof     VARCHAR(255)    DEFAULT NULL,
    created_at        TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at        TIMESTAMP       NULL DEFAULT NULL,

    CONSTRAINT fk_transactions_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_transactions_room
        FOREIGN KEY (room_id) REFERENCES rooms(id)
        ON DELETE CASCADE,

    INDEX idx_transactions_room_status (room_id, status, expires_at),
    INDEX idx_transactions_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 4. Seed admin (password: admin123)
-- -----------------------------------------------------------
INSERT INTO users (username, password_hash, role)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
