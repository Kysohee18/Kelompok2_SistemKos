<?php
// ============================================================
// config.php — PDO & Global Bootstrap
// website_ready — Monolithic Demo
// ============================================================

error_reporting(E_ALL);
ini_set('display_errors', '1');

date_default_timezone_set('Asia/Jakarta');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'kosbooking');
define('DB_USER', 'root');
define('DB_PASS', ''); //ini "Magelang2005" diganti jadi kosong karena rootnya passwordnya kosong, jadi sesuaikan dengan root masing2
define('DB_CHARSET', 'utf8mb4');

define('BASE_PATH', __DIR__);
define('UPLOAD_PATH', BASE_PATH . '/uploads');

try {
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
    );

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    error_log('DB connection failed: ' . $e->getMessage());
    die('Koneksi database gagal. Silakan coba lagi nanti.');
}

require_once BASE_PATH . '/includes/helpers.php';
require_once BASE_PATH . '/actions/state_machine.php';

cleanupExpiredDrafts($pdo);
