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

$name = trim((string)($_POST['id_agenda_kegiatan'] ?? ''));

if ($name === '') {
    http_response_code(400);
    exit('Id Agenda Kegiatan tidak valid.');
}

try {
    $res = qparams('SELECT "foto" FROM "agenda_kegiatan" WHERE "id_agenda_kegiatan" = $1', [$name]);
    $row = pg_fetch_assoc($res);
    if ($row && !empty($row['foto'])) {
        $foto = $row['foto'];
        $file_path = __DIR__ . '/' . $foto;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    qparams('DELETE FROM "agenda_kegiatan" WHERE "id_agenda_kegiatan" = $1', [$name]);
    header('Location: galeri.php');
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Gagal menghapus: Query gagal: ' . htmlspecialchars($e->getMessage());
}