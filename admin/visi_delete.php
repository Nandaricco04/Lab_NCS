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

$name = trim((string)($_POST['id_visi'] ?? ''));

if ($name === '') {
    http_response_code(400);
    exit('Id Visi tidak valid.');
}

try {
    qparams('DELETE FROM "visi" WHERE "id_visi" = $1', [$name]);
    header('Location: visi.php');
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Gagal menghapus: Query gagal: ' . htmlspecialchars($e->getMessage());
}