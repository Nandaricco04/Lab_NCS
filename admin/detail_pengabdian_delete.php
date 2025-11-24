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

$name = trim((string)($_POST['id_detail_pengabdian'] ?? ''));

if ($name === '') {
    http_response_code(400);
    exit('Id Detail Pengabdian tidak valid.');
}

try {
    qparams('DELETE FROM "detail_pengabdian" WHERE "id_detail_pengabdian" = $1', [$name]);
    header('Location: detail_pengabdian.php');
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Gagal menghapus: Query gagal: ' . htmlspecialchars($e->getMessage());
}