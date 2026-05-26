<?php

const ALLOWED_TRANSITIONS = [
    'DRAFT'              => ['PENDING_PAYMENT', 'CANCELLED'],
    'PENDING_PAYMENT'    => ['WAITING_VALIDATION', 'CANCELLED'],
    'WAITING_VALIDATION' => ['ACTIVE', 'CANCELLED'],
    'ACTIVE'             => [],
    'CANCELLED'          => [],
];

const LOCK_STATUSES = ['DRAFT', 'PENDING_PAYMENT', 'WAITING_VALIDATION', 'ACTIVE'];

function canTransition(string $current, string $next): bool
{
    return in_array($next, ALLOWED_TRANSITIONS[$current] ?? []);
}

function isRoomLocked(PDO $pdo, int $roomId): bool
{
    $placeholders = implode(',', array_fill(0, count(LOCK_STATUSES), '?'));
    $sql = "SELECT COUNT(*) FROM transactions
            WHERE room_id = ? AND status IN ($placeholders)
            AND (status != 'DRAFT' OR expires_at > NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_merge([$roomId], LOCK_STATUSES));
    return (int) $stmt->fetchColumn() > 0;
}

function transitionStatus(PDO $pdo, int $transactionId, string $newStatus, ?string $userId = null): bool
{
    $sql = 'SELECT status, room_id FROM transactions WHERE id = ?';
    $params = [$transactionId];
    if ($userId) {
        $sql .= ' AND user_id = ?';
        $params[] = $userId;
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $tx = $stmt->fetch();

    if (!$tx || !canTransition($tx['status'], $newStatus)) return false;

    $updateStmt = $pdo->prepare('UPDATE transactions SET status = ? WHERE id = ?');
    $updateStmt->execute([$newStatus, $transactionId]);
    return true;
}

function cleanupExpiredDrafts(PDO $pdo): void
{
    $pdo->exec("UPDATE transactions SET status = 'CANCELLED'
                WHERE status = 'DRAFT' AND expires_at IS NOT NULL AND expires_at <= NOW()");
}

function getRemainingTTL(PDO $pdo, int $transactionId): int
{
    $stmt = $pdo->prepare("SELECT TIMESTAMPDIFF(SECOND, NOW(), expires_at) FROM transactions WHERE id = ? AND status = 'DRAFT'");
    $stmt->execute([$transactionId]);
    return max(0, (int) $stmt->fetchColumn());
}

function createDraftTransaction(PDO $pdo, int $userId, int $roomId, int $durationMonths): int
{
    if (isRoomLocked($pdo, $roomId)) {
        throw new RuntimeException('Kamar sedang tidak tersedia untuk dipesan.');
    }

    $stmt = $pdo->prepare('SELECT price_per_month, status FROM rooms WHERE id = ?');
    $stmt->execute([$roomId]);
    $room = $stmt->fetch();

    if (!$room || $room['status'] !== 'Available') {
        throw new RuntimeException('Kamar tidak ditemukan atau tidak tersedia.');
    }

    $price   = (float) $room['price_per_month'];
    $total   = $price * $durationMonths;
    $deposit = $price * 0.5;

    $insert = $pdo->prepare("INSERT INTO transactions (user_id, room_id, duration_months, deposit, total_amount, status, expires_at)
                             VALUES (?, ?, ?, ?, ?, 'DRAFT', DATE_ADD(NOW(), INTERVAL 15 MINUTE))");
    $insert->execute([$userId, $roomId, $durationMonths, $deposit, $total]);
    return (int) $pdo->lastInsertId();
}
