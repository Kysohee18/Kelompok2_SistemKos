<?php
$action = $_GET['action'] ?? 'list';
$roomId = (int) ($_GET['id'] ?? 0);

$persistedErrors = $_SESSION['_form_errors'] ?? [];
$persistedData   = $_SESSION['_form_data'] ?? [];
unset($_SESSION['_form_errors'], $_SESSION['_form_data']);

if ($action === 'edit' && $roomId > 0) {
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE id = ?');
    $stmt->execute([$roomId]);
    $roomCheck = $stmt->fetch();
    if (!$roomCheck) { setFlash('error', 'Kamar tidak ditemukan.'); redirect('?page=admin_dashboard&tab=rooms'); }
}
?>
<?php if ($action === 'create' || ($action === 'edit' && $roomId > 0)):
    $room = ['name' => '', 'description' => '', 'facilities' => '', 'price_per_month' => '', 'status' => 'Available', 'image_path' => null];
    if ($action === 'edit') {
        $room = $roomCheck;
    }
    $errors = $persistedErrors;
    if (!empty($persistedData)) {
        $room['name'] = $persistedData['name'] ?? $room['name'];
        $room['description'] = $persistedData['description'] ?? $room['description'];
        $room['facilities'] = $persistedData['facilities'] ?? $room['facilities'];
        $room['price_per_month'] = $persistedData['price'] ?? $room['price_per_month'];
        $room['status'] = $persistedData['status'] ?? $room['status'];
    }
?>
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h1 class="text-xl font-bold text-gray-900 mb-6"><?= $action === 'create' ? 'Tambah Kamar Baru' : 'Edit Kamar' ?></h1>
            <?php foreach ($errors as $err): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm"><?= e($err) ?></div>
            <?php endforeach; ?>
            <form method="POST" action="?page=admin_process&action=save_room<?= $action === 'edit' ? '&id=' . $roomId : '' ?>" enctype="multipart/form-data" class="space-y-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kamar</label>
                    <input type="text" id="name" name="name" required value="<?= e($room['name']) ?>"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand"><?= e($room['description']) ?></textarea>
                </div>
                <div>
                    <label for="facilities" class="block text-sm font-medium text-gray-700 mb-1">Fasilitas</label>
                    <input type="text" id="facilities" name="facilities" value="<?= e($room['facilities']) ?>"
                           placeholder="K. Mandi Dalam, WiFi, AC, ..."
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="price_per_month" class="block text-sm font-medium text-gray-700 mb-1">Harga per Bulan</label>
                        <input type="number" id="price_per_month" name="price_per_month" required min="0" value="<?= e($room['price_per_month']) ?>"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                            <option value="Available" <?= $room['status'] === 'Available' ? 'selected' : '' ?>>Available</option>
                            <option value="Maintenance" <?= $room['status'] === 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Kamar</label>
                    <?php if ($room['image_path']): ?>
                        <div class="mb-2">
                            <img src="<?= e($room['image_path']) ?>" alt="Room" class="h-24 rounded-lg object-cover">
                            <p class="text-xs text-gray-400 mt-1">Kosongkan untuk menggunakan gambar yang sama.</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-brand-50 file:text-brand hover:file:bg-brand-100">
                    <p class="text-xs text-gray-400 mt-1">JPG/PNG, maks 2 MB.</p>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="bg-brand hover:bg-emerald-600 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                        <?= $action === 'create' ? 'Simpan' : 'Perbarui' ?>
                    </button>
                    <a href="?page=admin_dashboard&tab=rooms" class="border border-gray-300 text-gray-700 font-medium px-6 py-2.5 rounded-lg hover:bg-gray-50 transition text-sm">Batal</a>
                </div>
            </form>
        </div>
    </div>
<?php else:
    $stmt = $pdo->query('SELECT * FROM rooms ORDER BY created_at DESC');
    $rooms = $stmt->fetchAll();
?>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-900">Daftar Kamar</h2>
        <a href="?page=admin_dashboard&tab=rooms&action=create"
           class="bg-brand hover:bg-emerald-600 text-white font-medium px-5 py-2.5 rounded-lg transition text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kamar
        </a>
    </div>

    <?php if (empty($rooms)): ?>
        <div class="text-center py-16 bg-white rounded-xl border border-gray-200">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <p class="text-gray-500 mb-4">Belum ada kamar yang ditambahkan.</p>
            <a href="?page=admin_dashboard&tab=rooms&action=create" class="inline-block bg-brand text-white px-6 py-2.5 rounded-lg hover:bg-emerald-600 transition text-sm">Tambah Kamar Pertama</a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto bg-white rounded-xl border border-gray-200 shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Gambar</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Nama</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Harga</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($rooms as $room): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <?php if ($room['image_path']): ?>
                                <img src="<?= e($room['image_path']) ?>" alt="" class="w-12 h-12 rounded-lg object-cover">
                            <?php else: ?>
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900"><?= e($room['name']) ?></td>
                        <td class="px-4 py-3 text-gray-700"><?= formatRupiah((float) $room['price_per_month']) ?></td>
                        <td class="px-4 py-3">
                            <?php if ($room['status'] === 'Available'): ?>
                                <span class="inline-block bg-green-100 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full">Available</span>
                            <?php else: ?>
                                <span class="inline-block bg-yellow-100 text-yellow-700 text-xs font-medium px-2.5 py-1 rounded-full">Maintenance</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="?page=admin_dashboard&tab=rooms&action=edit&id=<?= (int) $room['id'] ?>" class="text-brand hover:text-emerald-600 font-medium text-xs">Edit</a>
                                <form method="POST" action="?page=admin_process&action=delete_room&id=<?= (int) $room['id'] ?>" class="inline" onsubmit="return confirm('Hapus kamar ini?')">
                                    <button type="submit" class="text-red-500 hover:text-red-600 font-medium text-xs">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<?php endif; ?>
