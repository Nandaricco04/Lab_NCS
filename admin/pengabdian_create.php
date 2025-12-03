<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$tahun = $judul = '';

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
                'INSERT INTO "pengabdian" ("id_pengabdian", "tahun", "judul") VALUES ($1, $2, $3)',
                [$id_pengabdian, $tahun, $judul]
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
    <title>Tambah Pengabdian</title>
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
                        Tambah Pengabdian
                    </div>
                    <form class="form-user-box" method="post" autocomplete="off">
                        <div class="desc-form">
                            Isi data pengabdian di bawah dengan lengkap dan benar.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="tahun" class="user-form-label">Tahun<span style="color:red">*</span></label>
                            <input type="text" name="tahun" id="tahun" class="user-form-input" value="<?= htmlspecialchars($tahun) ?>" required autocomplete="off" maxlength="4" pattern="[0-9]{4}" placeholder="Contoh: 2024">
                        </div>
                        <div class="form-group">
                            <label for="judul" class="user-form-label">Judul<span style="color:red">*</span></label>
                            <input type="text" name="judul" id="judul" class="user-form-input" value="<?= htmlspecialchars($judul) ?>" required autocomplete="off" placeholder="Masukkan Judul Pengabdian">
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