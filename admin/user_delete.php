<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$name = trim((string)($_POST['id_pengguna'] ?? ''));

if ($name === '') {
    http_response_code(400);
    exit('Id Ruang tidak valid.');
}

try {
    qparams('DELETE FROM "users" WHERE "id_pengguna" = $1', [$name]);
    header('Location: user.php');
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Gagal menghapus: Query gagal: ' . htmlspecialchars($e->getMessage());
}