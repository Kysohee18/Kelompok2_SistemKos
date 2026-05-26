<?php

$action = $_GET['action'] ?? '';
$txId   = (int) ($_GET['id'] ?? 0);

if ($txId <= 0 || !in_array($action, ['approve', 'reject'])) {
    setFlash('error', 'Aksi tidak valid.');
    redirect('?page=admin_transactions');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('?page=admin_transactions');
}

if ($action === 'approve') {
    $success = transitionStatus($pdo, $txId, 'ACTIVE');
    setFlash($success ? 'success' : 'error',
        $success ? 'Pembayaran diverifikasi! Transaksi sekarang ACTIVE.' : 'Gagal memverifikasi transaksi.');
} else {
    $success = transitionStatus($pdo, $txId, 'CANCELLED');
    setFlash($success ? 'success' : 'error',
        $success ? 'Pembayaran ditolak. Kamar telah dibuka kembali.' : 'Gagal menolak transaksi.');
}

redirect('?page=admin_transactions');
