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

$id_konsultasi = trim((string)($_POST['id_konsultasi'] ?? ''));

if ($id_konsultasi === '') {
    http_response_code(400);
    exit('Id Konsultasi tidak valid.');
}

try {
    qparams('DELETE FROM "konsultasi" WHERE "id_konsultasi" = $1', [$id_konsultasi]);
    header('Location: konsultasi.php');
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Gagal menghapus: Query gagal: ' . htmlspecialchars($e->getMessage());
}