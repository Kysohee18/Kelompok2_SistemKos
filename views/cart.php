<?php
$userId = (int) ($_SESSION['user_id'] ?? 0);

// Proteksi jika user belum login
if ($userId === 0) {
    setFlash('error', 'Silakan login terlebih dahulu untuk melihat keranjang.');
    redirect('?page=login');
}

// FITUR TAMBAH / KURANG KUANTITAS
if (isset($_GET['action']) && in_array($_GET['action'], ['inc', 'dec'])) {
    $cartId = (int) ($_GET['id'] ?? 0);
    
    $checkStmt = $pdo->prepare('SELECT quantity FROM cart WHERE id = ? AND user_id = ?');
    $checkStmt->execute([$cartId, $userId]);
    $currentQty = $checkStmt->fetchColumn();

    if ($currentQty !== false) {
        if ($_GET['action'] === 'inc') {
            $newQty = $currentQty + 1;
        } else {
            $newQty = max(1, $currentQty - 1);
        }

        $updateStmt = $pdo->prepare('UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?');
        $updateStmt->execute([$newQty, $cartId, $userId]);
    }
    redirect('?page=cart');
}

// Fitur Hapus Item dari Keranjang
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $cartId = (int) ($_GET['id'] ?? 0);
    $deleteStmt = $pdo->prepare('DELETE FROM cart WHERE id = ? AND user_id = ?');
    $deleteStmt->execute([$cartId, $userId]);

    setFlash('success', 'Item berhasil dihapus dari keranjang.');
    redirect('?page=cart');
}

// PROSES CHECKOUT KETIKA TOMBOL DIKLIK
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proses_checkout'])) {
    $stmt = $pdo->prepare('
        SELECT c.room_id, r.price_per_month, c.quantity FROM cart c 
        JOIN rooms r ON c.room_id = r.id 
        WHERE c.user_id = ? AND r.status = "Available"
    ');
    $stmt->execute([$userId]);
    $itemsToOrder = $stmt->fetchAll();

    if (!empty($itemsToOrder)) {
        $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        
        // Memproses item pertama di keranjang untuk checkout
        $item = $itemsToOrder[0];
        $qty = (int) $item['quantity'];
        $price = (float) $item['price_per_month'] * $qty;
        $deposit = $price * 0.5; // Deposit 50%
        
        $insertTx = $pdo->prepare("
            INSERT INTO transactions (user_id, room_id, duration_months, total_amount, deposit, status, expires_at, created_at) 
            VALUES (?, ?, ?, ?, ?, 'DRAFT', ?, NOW())
        ");
        $insertTx->execute([$userId, $item['room_id'], $qty, $price, $deposit, $expiresAt]);
        $newTxId = $pdo->lastInsertId();

        $deleteCart = $pdo->prepare('DELETE FROM cart WHERE user_id = ? AND room_id = ?');
        $deleteCart->execute([$userId, $item['room_id']]);

        redirect('?page=checkout&t=' . $newTxId);
    } else {
        setFlash('error', 'Tidak ada kamar yang tersedia untuk diproses.');
        redirect('?page=cart');
    }
}

// MENGAMBIL DATA KERANJANG (Sudah ditambahkan c.quantity)
$stmt = $pdo->prepare('
    SELECT c.id as cart_id, c.quantity, r.* FROM cart c 
    JOIN rooms r ON c.room_id = r.id 
    WHERE c.user_id = ?
');
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll();

// Menghitung total harga berdasarkan (Harga Kamar x Kuantitas)
$totalPrice = 0;
$availableItemsCount = 0;
foreach ($cartItems as $item) {
    if ($item['status'] === 'Available') {
        $totalPrice += ((float) $item['price_per_month'] * (int) $item['quantity']);
        $availableItemsCount += (int) $item['quantity'];
    }
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Keranjang Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar item pilihan Anda sebelum melakukan booking / checkout.</p>
    </div>

    <?php if (empty($cartItems)): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Keranjang Kosong</h3>
            <p class="text-sm text-gray-500 mb-6">Anda belum menambahkan item apa pun ke dalam keranjang.</p>
            <a href="?page=catalog" class="inline-flex items-center justify-center bg-brand hover:bg-emerald-600 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition">
                Lihat Katalog
            </a>
        </div>
    <?php else: ?>
        <div class="grid lg:grid-cols-3 gap-6 items-start">

            <div class="lg:col-span-2 space-y-4">
                <?php foreach ($cartItems as $item): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
                        <div class="flex items-center flex-shrink-0">
                            <input type="checkbox" checked class="w-4 h-4 text-brand border-gray-300 rounded focus:ring-brand">
                        </div>

                        <div class="flex gap-4 items-center flex-1 min-w-0">
                            <div class="w-20 h-20 rounded-lg bg-gradient-to-br from-brand-100 to-brand-200 flex items-center justify-center flex-shrink-0 border border-gray-100">
                                <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h2 class="text-base font-bold text-gray-900 truncate"><?= e($item['name']) ?></h2>
                                <p class="text-xs text-gray-400 mt-0.5 truncate"><?= e($item['facilities'] ?: 'Spesifikasi standar') ?></p>
                                <p class="text-brand font-bold text-sm mt-1"><?= formatRupiah((float) $item['price_per_month']) ?></p>
                                
                                <div class="mt-1">
                                    <?php if ($item['status'] === 'Available'): ?>
                                        <span class="text-xs text-green-600 font-medium bg-green-50 px-2 py-0.5 rounded">Tersedia</span>
                                    <?php else: ?>
                                        <span class="text-xs text-red-600 font-medium bg-red-50 px-2 py-0.5 rounded">Penuh</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-end justify-between gap-4 flex-shrink-0">
                            <a href="?page=cart&action=delete&id=<?= $item['cart_id'] ?>"
                                onclick="return confirm('Hapus item ini dari keranjang?')"
                                class="text-gray-400 hover:text-red-500 transition p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </a>

                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                                <a href="?page=cart&action=dec&id=<?= $item['cart_id'] ?>" class="px-2.5 py-1 text-gray-600 hover:bg-gray-200 transition font-bold text-sm">-</a>
                                <span class="px-3 py-1 text-xs font-semibold text-gray-800 bg-white border-x border-gray-200 min-w-[2rem] text-center">
                                    <?= $item['quantity'] ?>
                                </span>
                                <a href="?page=cart&action=inc&id=<?= $item['cart_id'] ?>" class="px-2.5 py-1 text-gray-600 hover:bg-gray-200 transition font-bold text-sm">+</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                <h3 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-100 pb-3">Ringkasan Belanja</h3>

                <div class="space-y-3 text-sm text-gray-600 mb-6">
                    <div class="flex justify-between">
                        <span>Total Kuota Sewa</span>
                        <span class="font-semibold text-gray-900"><?= $availableItemsCount ?> Bulan</span>
                    </div>
                    <hr class="border-gray-100 my-2">
                    <div class="flex justify-between items-baseline">
                        <span class="font-medium text-gray-900">Total Harga</span>
                        <span class="text-2xl font-extrabold text-brand"><?= formatRupiah($totalPrice) ?></span>
                    </div>
                </div>

                <div>
                    <?php if ($availableItemsCount > 0): ?>
                        <form method="POST" action="">
                            <button type="submit" name="proses_checkout"
                                class="block w-full text-center bg-brand hover:bg-emerald-600 text-white font-bold py-3 rounded-xl transition shadow-md shadow-emerald-100 hover:-translate-y-0.5 text-sm cursor-pointer">
                                Lanjutkan ke Checkout
                            </button>
                        </form>
                    <?php else: ?>
                        <button disabled class="w-full bg-gray-200 text-gray-400 font-bold py-3 rounded-xl cursor-not-allowed text-sm">
                            Barang Tidak Tersedia
                        </button>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    <?php endif; ?>
</div>