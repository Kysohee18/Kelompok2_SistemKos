<?php

$action = $_GET['action'] ?? '';

switch ($action) {

    // ============================================================
    case 'delete_room':
    // ============================================================
        $roomId = (int) ($_GET['id'] ?? 0);
        if ($roomId <= 0 || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            setFlash('error', 'Aksi tidak valid.');
            redirect('?page=admin_dashboard&tab=rooms');
        }

        $stmt = $pdo->prepare('SELECT image_path FROM rooms WHERE id = ?');
        $stmt->execute([$roomId]);
        $room = $stmt->fetch();

        if ($room && $room['image_path']) {
            $imgPath = __DIR__ . '/../' . $room['image_path'];
            if (file_exists($imgPath)) unlink($imgPath);
        }

        $stmt = $pdo->prepare('DELETE FROM rooms WHERE id = ?');
        $stmt->execute([$roomId]);
        setFlash('success', 'Kamar berhasil dihapus.');
        redirect('?page=admin_dashboard&tab=rooms');

    // ============================================================
    case 'save_room':
    // ============================================================
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('?page=admin_dashboard&tab=rooms');
        }

        $roomId     = (int) ($_GET['id'] ?? 0);
        $name       = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $facilities = trim($_POST['facilities'] ?? '');
        $price      = (float) ($_POST['price_per_month'] ?? 0);
        $status     = $_POST['status'] ?? 'Available';
        $isEdit     = ($roomId > 0);

        $errors = [];
        if ($name === '') $errors[] = 'Nama kamar harus diisi.';
        if ($price <= 0) $errors[] = 'Harga harus lebih dari 0.';

        $imagePath = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // MIME type validation via finfo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $_FILES['image']['tmp_name']);
            finfo_close($finfo);
            $allowedMimes = ['image/jpeg', 'image/png'];
            if (!in_array($mime, $allowedMimes)) {
                $errors[] = 'File gambar harus JPG atau PNG (MIME: ' . e($mime) . ').';
            } elseif (!validateFileSize($_FILES['image'])) {
                $errors[] = 'Ukuran file maksimal 2 MB.';
            } else {
                $uploadDir = __DIR__ . '/../uploads/rooms/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $ext   = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $nameF = 'room_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $dest  = $uploadDir . $nameF;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    if (in_array($ext, ['jpg', 'jpeg'])) {
                        $img = @imagecreatefromjpeg($dest);
                        if ($img) { imagejpeg($img, $dest, 75); imagedestroy($img); }
                    } elseif ($ext === 'png') {
                        $img = @imagecreatefrompng($dest);
                        if ($img) { imagesavealpha($img, true); imagepng($img, $dest, 6); imagedestroy($img); }
                    }
                    $imagePath = 'uploads/rooms/' . $nameF;
                } else {
                    $errors[] = 'Gagal mengunggah gambar.';
                }
            }
        } elseif ($isEdit && empty($_FILES['image']['name'])) {
            $stmt = $pdo->prepare('SELECT image_path FROM rooms WHERE id = ?');
            $stmt->execute([$roomId]);
            $existing = $stmt->fetch();
            $imagePath = $existing['image_path'] ?? null;
        }

        if (!empty($errors)) {
            $_SESSION['_form_errors'] = $errors;
            $_SESSION['_form_data'] = compact('name', 'description', 'facilities', 'price', 'status');
            $queryParams = $isEdit ? '&action=edit&id=' . $roomId : '&action=create';
            redirect('?page=admin_dashboard&tab=rooms' . $queryParams);
        }

        if ($isEdit) {
            $stmt = $pdo->prepare('UPDATE rooms SET name=?, description=?, facilities=?, price_per_month=?, status=?, image_path=? WHERE id=?');
            $stmt->execute([$name, $description, $facilities, $price, $status, $imagePath, $roomId]);
            setFlash('success', 'Kamar berhasil diperbarui.');
        } else {
            $stmt = $pdo->prepare('INSERT INTO rooms (name, description, facilities, price_per_month, status, image_path) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$name, $description, $facilities, $price, $status, $imagePath]);
            setFlash('success', 'Kamar berhasil ditambahkan.');
        }
        redirect('?page=admin_dashboard&tab=rooms');

    // ============================================================
    case 'approve_tx':
    case 'reject_tx':
    // ============================================================
        $txId = (int) ($_GET['id'] ?? 0);

        if ($txId <= 0 || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            setFlash('error', 'Aksi tidak valid.');
            redirect('?page=admin_dashboard&tab=transactions');
        }

        if ($action === 'approve_tx') {
            $success = transitionStatus($pdo, $txId, 'ACTIVE');
            setFlash($success ? 'success' : 'error',
                $success ? 'Pembayaran diverifikasi! Transaksi sekarang ACTIVE.' : 'Gagal memverifikasi transaksi.');
        } else {
            $success = transitionStatus($pdo, $txId, 'CANCELLED');
            setFlash($success ? 'success' : 'error',
                $success ? 'Pembayaran ditolak. Kamar telah dibuka kembali.' : 'Gagal menolak transaksi.');
        }
        redirect('?page=admin_dashboard&tab=transactions');

    // ============================================================
    default:
        setFlash('error', 'Aksi tidak dikenali.');
        redirect('?page=admin_dashboard');
}
