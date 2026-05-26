<?php

function formatRupiah(float $amount): string
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function flash(string $key): ?string
{
    if (isset($_SESSION['_flash'][$key])) {
        $msg = $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);
        return $msg;
    }
    return null;
}

function setFlash(string $key, string $value): void
{
    $_SESSION['_flash'][$key] = $value;
}

function isAuthenticated(): bool
{
    return !empty($_SESSION['user_id']);
}

function isAdmin(): bool
{
    return ($_SESSION['role'] ?? '') === 'admin';
}

function authId(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

function validateFileType(array $file, array $allowed = ['jpg', 'jpeg', 'png']): bool
{
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    return in_array($ext, $allowed, true);
}

function validateFileSize(array $file, float $maxMb = 2.0): bool
{
    return $file['size'] <= $maxMb * 1024 * 1024;
}
