<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$id = (string)($_GET['id'] ?? $_GET['id_pengabdian'] ?? '');

if ($id === '') {
    http_response_code(400);
    exit('ID tidak valid.');
}

try {
    $res = qparams('SELECT "tahun", "judul" FROM "pengabdian" WHERE "id_pengabdian"=$1', [$id]);
    $row = pg_fetch_assoc($res);
    if (!$row) {
        http_response_code(404);
        exit('Data tidak ditemukan.');
    }
} catch (Throwable $e) {
    exit('Error: ' . htmlspecialchars($e->getMessage()));
}

$tahun = $row['tahun'];
$judul = $row['judul'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tahun = trim($_POST['tahun'] ?? '');
    $judul = trim($_POST['judul'] ?? '');

    if ($tahun === '' || $judul === '') {
        $err = 'Semua field wajib diisi.';
    } elseif (!preg_match('/^[0-9]{4}$/', $tahun)) {
        $err = 'Tahun harus berupa 4 digit angka.';
    } else {
        try {
            qparams(
                'UPDATE "pengabdian" SET "tahun"=$1, "judul"=$2 WHERE "id_pengabdian"=$3',
                [$tahun, $judul, $id_pengabdian]
            );
            header('Location: pengabdian.php');
            exit;
        } catch (Throwable $e) {
            $err = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ubah Pengabdian</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
</head>
<body>
    <div class="layout">
        <?php include 'sidebar.php'; ?>
        <div class="content">
            <div class="flex-center">
                <div class="card-wrapper">
                    <div class="main-table-title" style="margin-bottom:16px;">
                        Ubah Pengabdian
                    </div>
                    <form class="form-user-box" method="post" autocomplete="off">
                        <div class="desc-form">
                            Ubah data pengabdian di bawah sesuai kebutuhan.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="tahun" class="user-form-label">Tahun</label>
                            <input type="text" name="tahun" id="tahun" class="user-form-input" value="<?= htmlspecialchars($tahun) ?>" required autocomplete="off" maxlength="4" pattern="[0-9]{4}" placeholder="Contoh: 2024">
                        </div>
                        <div class="form-group">
                            <label for="judul" class="user-form-label">Judul</label>
                            <textarea name="judul" id="judul" class="user-form-input" required autocomplete="off" placeholder="Masukkan Judul Pengabdian"><?= htmlspecialchars($judul) ?></textarea>
                        </div>
                        <div class="form-btn-bar" style="margin-top:32px;">
                            <button class="btn-user-primary" type="submit">
                                <span class="iconify" data-icon="mdi:content-save"></span> Simpan
                            </button>
                            <a class="btn-user-warning" href="pengabdian.php">
                                <span class="iconify" data-icon="mdi:arrow-left"></span> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>