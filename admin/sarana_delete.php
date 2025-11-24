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

$name = trim((string)($_POST['id_sarana_prasarana'] ?? ''));

if ($name === '') {
    http_response_code(400);
    exit('Id Sarana Prasarana tidak valid.');
}

try {
    qparams('DELETE FROM "sarana_prasarana" WHERE "id_sarana_prasarana" = $1', [$name]);
    header('Location: sarana.php');
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Gagal menghapus: Query gagal: ' . htmlspecialchars($e->getMessage());
}