<?php
session_start();

if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$id_konsultasi = $nama_konsultasi = $nim_konsultasi = $isi_konsultasi = $tanggal_konsultasi = $email_konsultasi = $no_wa_konsultasi = '';
$status = 'Proses';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_konsultasi      = trim($_POST['id_konsultasi'] ?? '');
    $nama_konsultasi    = trim($_POST['nama_konsultasi'] ?? '');
    $nim_konsultasi     = trim($_POST['nim_konsultasi'] ?? '');
    $isi_konsultasi     = trim($_POST['isi_konsultasi'] ?? '');
    $tanggal_konsultasi = trim($_POST['tanggal_konsultasi'] ?? '');
    $email_konsultasi   = trim($_POST['email_konsultasi'] ?? '');
    $no_wa_konsultasi   = trim($_POST['no_wa_konsultasi'] ?? '');
    $status             = trim($_POST['status'] ?? 'proses');

    if ($id_konsultasi === '' || $nama_konsultasi === '' || $nim_konsultasi === '' || $isi_konsultasi === '' || $tanggal_konsultasi === '' || $email_konsultasi === '' || $no_wa_konsultasi === '' || $status === '') {
        $err = 'Semua field wajib diisi.';
    } else {
        try {
            qparams(
                'INSERT INTO "konsultasi" ("id_konsultasi", "nama_konsultasi", "nim_konsultasi", "isi_konsultasi", "tanggal_konsultasi", "email_konsultasi", "no_wa_konsultasi", "status") VALUES ($1, $2, $3, $4, $5, $6, $7, $8)',
                [$id_konsultasi, $nama_konsultasi, $nim_konsultasi, $isi_konsultasi, $tanggal_konsultasi, $email_konsultasi, $no_wa_konsultasi, $status]
            );
            header('Location: konsultasi.php');
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
    <title>Tambah Konsultasi</title>
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
                        Tambah Konsultasi
                    </div>
                    <form class="form-user-box" method="post" autocomplete="off">
                        <div class="desc-form">
                            Isi data konsultasi di bawah dengan lengkap dan benar.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="id_konsultasi" class="user-form-label">Id Konsultasi</label>
                            <input type="text" name="id_konsultasi" id="id_konsultasi" class="user-form-input" value="<?= htmlspecialchars($id_konsultasi) ?>" required autocomplete="off" placeholder="Masukkan Id Konsultasi">
                        </div>
                        <div class="form-group">
                            <label for="nama_konsultasi" class="user-form-label">Nama</label>
                            <input type="text" name="nama_konsultasi" id="nama_konsultasi" class="user-form-input" value="<?= htmlspecialchars($nama_konsultasi) ?>" required autocomplete="off" placeholder="Masukkan Nama Konsultasi">
                        </div>
                        <div class="form-group">
                            <label for="nim_konsultasi" class="user-form-label">NIM</label>
                            <input type="text" name="nim_konsultasi" id="nim_konsultasi" class="user-form-input" value="<?= htmlspecialchars($nim_konsultasi) ?>" required autocomplete="off" placeholder="Masukkan NIM Konsultasi">
                        </div>
                        <div class="form-group">
                            <label for="isi_konsultasi" class="user-form-label">Isi Konsultasi</label>
                            <textarea name="isi_konsultasi" id="isi_konsultasi" class="user-form-input" required autocomplete="off" placeholder="Masukkan Isi Konsultasi"><?= htmlspecialchars($isi_konsultasi) ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_konsultasi" class="user-form-label">Tanggal Konsultasi</label>
                            <input type="date" name="tanggal_konsultasi" id="tanggal_konsultasi" class="user-form-input" value="<?= htmlspecialchars($tanggal_konsultasi) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email_konsultasi" class="user-form-label">Email</label>
                            <input type="email" name="email_konsultasi" id="email_konsultasi" class="user-form-input" value="<?= htmlspecialchars($email_konsultasi) ?>" required autocomplete="off" placeholder="Masukkan Email">
                        </div>
                        <div class="form-group">
                            <label for="no_wa_konsultasi" class="user-form-label">No. WA</label>
                            <input type="text" name="no_wa_konsultasi" id="no_wa_konsultasi" class="user-form-input" value="<?= htmlspecialchars($no_wa_konsultasi) ?>" required autocomplete="off" placeholder="Masukkan No WA Konsultasi">
                        </div>
                        <div class="form-group">
                            <label for="status" class="user-form-label">Status</label>
                            <select name="status" id="status" class="user-form-input" required>
                                <option value="Proses" <?= ($status === 'Proses' ? 'selected' : '') ?>>Proses</option>
                                <option value="Selesai" <?= ($status === 'Selesai' ? 'selected' : '') ?>>Selesai</option>
                            </select>
                        </div>
                        <div class="form-btn-bar" style="margin-top:32px;">
                            <button class="btn-user-primary" type="submit">
                                <span class="iconify" data-icon="mdi:content-save"></span> Simpan
                            </button>
                            <a class="btn-user-warning" href="konsultasi.php">
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