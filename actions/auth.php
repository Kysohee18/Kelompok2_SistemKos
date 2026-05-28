<?php

$page = $_GET['page'] ?? '';

if ($page === 'logout') {
    $_SESSION = [];

    session_unset();
    session_destroy();

    header("Location: ?page=home");
    exit;
}
