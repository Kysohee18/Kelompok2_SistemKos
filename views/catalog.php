<?php require __DIR__ . '/../includes/layout.php'; ?>
<?php
$search = trim($_GET['search'] ?? '');

$sql = "SELECT * FROM rooms WHERE status = 'Available'";
$params = [];
if ($search !== '') {
    $sql .= " AND (name LIKE ? OR facilities LIKE ?)";
    $like = '%' . $search . '%';
    $params[] = $like;
    $params[] = $like;
}
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rooms = $stmt->fetchAll();
?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Cari Kost</h1>
                <p class="text-sm text-gray-500 mt-1">Temukan kost impianmu dengan mudah</p>
            </div>
            <?php if ($search !== ''): ?>
                <a href="?page=catalog" class="text-sm text-brand hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Hapus filter
                </a>
            <?php endif; ?>
        </div>

        <?php if (empty($rooms)): ?>
            <div class="text-center py-20">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <h2 class="text-xl font-semibold text-gray-600 mb-2">Kost tidak ditemukan</h2>
                <p class="text-gray-400 text-sm mb-4">
                    <?= $search ? "Kost dengan kata kunci \"$search\" tidak tersedia." : 'Belum ada kost yang ditambahkan.' ?>
                </p>
                <a href="?page=catalog" class="inline-block bg-brand text-white px-6 py-2.5 rounded-lg hover:bg-emerald-600 transition text-sm font-medium">Lihat Semua Kost</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($rooms as $room): ?>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg transition-shadow duration-300 flex flex-col overflow-hidden">
                    <div class="relative h-48 bg-gray-200 overflow-hidden">
                        <?php if ($room['image_path'] && file_exists(__DIR__ . '/../' . $room['image_path'])): ?>
                            <img src="<?= e($room['image_path']) ?>" alt="<?= e($room['name']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-brand-100 to-brand-200">
                                <svg class="w-12 h-12 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                        <?php endif; ?>
                        <div class="absolute bottom-0 left-0 bg-kos-purple text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-tr-lg">SUPER RARE KOST</div>
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <div class="flex items-center gap-2 text-[11px] mb-2">
                            <span class="border border-gray-300 text-gray-600 rounded px-1.5 py-0.5 font-medium">Campur</span>
                            <span class="text-yellow-500 flex items-center gap-0.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                4.5
                            </span>
                            <span class="text-red-500 font-medium ml-auto">Sisa 1 kamar</span>
                        </div>
                        <h3 class="text-base font-bold text-gray-900 leading-tight mb-1 line-clamp-2"><?= e($room['name']) ?></h3>
                        <p class="text-sm text-gray-500 truncate mb-3"><?= e($room['facilities'] ?: 'Fasilitas tidak tersedia') ?></p>
                        <div class="flex-1"></div>
                        <div class="border-t border-gray-100 pt-3 mt-auto">
                            <div class="flex items-center gap-1.5 text-xs mb-1">
                                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span class="text-red-500 font-medium">Diskon Spesial</span>
                                <span class="text-gray-400 line-through ml-auto"><?= formatRupiah((float) $room['price_per_month'] * 1.2) ?></span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl font-bold text-gray-900"><?= formatRupiah((float) $room['price_per_month']) ?></span>
                                <span class="text-sm text-gray-500">/bln</span>
                            </div>
                        </div>
                        <a href="?page=booking&room=<?= (int) $room['id'] ?>"
                           class="mt-3 block w-full bg-brand hover:bg-emerald-600 text-white text-center font-semibold py-2.5 rounded-lg transition text-sm">Pesan Sekarang</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
