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

$name = trim((string)($_POST['id_pengelola'] ?? ''));

if ($name === '') {
    http_response_code(400);
    exit('Id Pengelola tidak valid.');
}

try {
    qparams('DELETE FROM "struktur_organisasi" WHERE "id_pengelola" = $1', [$name]);
    header('Location: struktur_organisasi.php');
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Gagal menghapus: Query gagal: ' . htmlspecialchars($e->getMessage());
}