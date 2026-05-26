<?php require __DIR__ . '/../includes/layout.php'; ?>
<?php
$tab = $_GET['tab'] ?? 'waiting';
$queryMap = [
    'all'       => "SELECT t.*, u.username, r.name AS room_name FROM transactions t JOIN users u ON t.user_id = u.id JOIN rooms r ON t.room_id = r.id ORDER BY t.created_at DESC",
    'waiting'   => "SELECT t.*, u.username, r.name AS room_name FROM transactions t JOIN users u ON t.user_id = u.id JOIN rooms r ON t.room_id = r.id WHERE t.status = 'WAITING_VALIDATION' ORDER BY t.created_at ASC",
    'active'    => "SELECT t.*, u.username, r.name AS room_name FROM transactions t JOIN users u ON t.user_id = u.id JOIN rooms r ON t.room_id = r.id WHERE t.status = 'ACTIVE' ORDER BY t.created_at DESC",
    'draft'     => "SELECT t.*, u.username, r.name AS room_name FROM transactions t JOIN users u ON t.user_id = u.id JOIN rooms r ON t.room_id = r.id WHERE t.status = 'DRAFT' ORDER BY t.created_at DESC",
    'cancelled' => "SELECT t.*, u.username, r.name AS room_name FROM transactions t JOIN users u ON t.user_id = u.id JOIN rooms r ON t.room_id = r.id WHERE t.status = 'CANCELLED' ORDER BY t.created_at DESC",
];
$sql = $queryMap[$tab] ?? $queryMap['waiting'];
$stmt = $pdo->query($sql);
$transactions = $stmt->fetchAll();
?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Verifikasi Transaksi</h1>

        <div class="flex flex-wrap gap-1 mb-6 border-b border-gray-200">
            <?php $tabs = ['waiting'=>'Menunggu', 'active'=>'Aktif', 'draft'=>'Draft', 'cancelled'=>'Dibatalkan', 'all'=>'Semua']; ?>
            <?php foreach ($tabs as $key => $label): ?>
                <a href="?page=admin_transactions&tab=<?= $key ?>"
                   class="px-5 py-2.5 text-sm font-medium rounded-t-lg transition
                          <?= $tab === $key ? 'bg-white text-brand border-l border-t border-r border-gray-200 -mb-px' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' ?>">
                    <?= $label ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($transactions)): ?>
            <div class="text-center py-16 bg-white rounded-xl border border-gray-200">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-gray-500">Tidak ada transaksi dengan status ini.</p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($transactions as $tx): ?>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 hover:shadow-md transition">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-bold text-gray-900"><?= e($tx['room_name']) ?></h3>
                                <?php
                                $map = ['DRAFT'=>'bg-gray-100 text-gray-700|Draft', 'PENDING_PAYMENT'=>'bg-yellow-100 text-yellow-700|Pending',
                                        'WAITING_VALIDATION'=>'bg-blue-100 text-blue-700|Menunggu', 'ACTIVE'=>'bg-green-100 text-green-700|Aktif', 'CANCELLED'=>'bg-red-100 text-red-700|Dibatalkan'];
                                [$cls, $lbl] = explode('|', $map[$tx['status']] ?? 'bg-gray-100 text-gray-700|' . $tx['status']);
                                ?>
                                <span class="inline-block text-xs font-medium px-2.5 py-0.5 rounded-full <?= $cls ?>"><?= $lbl ?></span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                                <div><span class="text-gray-400 text-xs">Penyewa</span><p class="font-medium text-gray-700"><?= e($tx['username']) ?></p></div>
                                <div><span class="text-gray-400 text-xs">Durasi</span><p class="font-medium text-gray-700"><?= (int) $tx['duration_months'] ?> bln</p></div>
                                <div><span class="text-gray-400 text-xs">Total</span><p class="font-medium text-gray-700"><?= formatRupiah((float) $tx['total_amount'] + (float) $tx['deposit']) ?></p></div>
                                <div><span class="text-gray-400 text-xs">Tanggal</span><p class="font-medium text-gray-700"><?= date('d/m/Y H:i', strtotime($tx['created_at'])) ?></p></div>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2 flex-shrink-0">
                            <?php if ($tx['status'] === 'WAITING_VALIDATION'): ?>
                                <?php if ($tx['payment_proof']): ?>
                                    <a href="<?= e($tx['payment_proof']) ?>" target="_blank" class="text-sm text-brand hover:underline mb-2">Lihat Bukti Pembayaran</a>
                                <?php endif; ?>
                                <div class="flex gap-2">
                                    <form method="POST" action="?page=admin_verify&action=approve&id=<?= (int) $tx['id'] ?>">
                                        <button type="submit" onclick="return confirm('Setujui pembayaran ini?')"
                                                class="bg-brand hover:bg-emerald-600 text-white font-medium px-4 py-2 rounded-lg transition text-sm">Setujui</button>
                                    </form>
                                    <form method="POST" action="?page=admin_verify&action=reject&id=<?= (int) $tx['id'] ?>">
                                        <button type="submit" onclick="return confirm('Tolak pembayaran ini? Kamar akan dibuka kembali.')"
                                                class="bg-red-500 hover:bg-red-600 text-white font-medium px-4 py-2 rounded-lg transition text-sm">Tolak</button>
                                    </form>
                                </div>
                            <?php elseif ($tx['status'] === 'DRAFT' && $tx['expires_at']): ?>
                                <p class="text-xs text-gray-400">TTL: <?= date('d/m/Y H:i', strtotime($tx['expires_at'])) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
