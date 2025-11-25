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

$name = trim((string)($_POST['id_peta_jalan'] ?? ''));

if ($name === '') {
    http_response_code(400);
    exit('Id Peta Jalan tidak valid.');
}

try {
    $res = qparams('SELECT "file_path" FROM "peta_jalan" WHERE "id_peta_jalan" = $1', [$name]);
    $row = pg_fetch_assoc($res);
    if ($row && !empty($row['file_path'])) {
        $file = __DIR__ . '/' . $row['file_path'];
        if (file_exists($file)) @unlink($file);
    }

    qparams('DELETE FROM "peta_jalan" WHERE "id_peta_jalan" = $1', [$name]);
    header('Location: peta_jalan.php');
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Gagal menghapus: Query gagal: ' . htmlspecialchars($e->getMessage());
}
