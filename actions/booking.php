<?php

$roomId = (int) ($_GET['room'] ?? 0);
$userId = (int) ($_SESSION['user_id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM rooms WHERE id = ?');
$stmt->execute([$roomId]);
$room = $stmt->fetch();

if (!$room) {
    setFlash('error', 'Kamar tidak ditemukan.');
    redirect('?page=catalog');
}

if ($room['status'] !== 'Available') {
    setFlash('error', 'Kamar sedang tidak tersedia.');
    redirect('?page=catalog');
}

if (isRoomLocked($pdo, $roomId)) {
    setFlash('error', 'Kamar sedang diproses oleh pengguna lain.');
    redirect('?page=catalog');
}

$duration = (int) ($_POST['duration_months'] ?? 0);
if ($duration < 1 || $duration > 24) {
    setFlash('error', 'Durasi sewa harus antara 1 - 24 bulan.');
    redirect('?page=booking&room=' . $roomId);
}

try {
    $txId = createDraftTransaction($pdo, $userId, $roomId, $duration);
    setFlash('success', 'Kamar berhasil dipesan! Silakan lanjutkan ke pembayaran.');
    redirect('?page=checkout&t=' . $txId);
} catch (RuntimeException $e) {
    setFlash('error', $e->getMessage());
    redirect('?page=booking&room=' . $roomId);
}
