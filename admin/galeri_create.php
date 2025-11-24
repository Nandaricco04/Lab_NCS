<?php
session_start();

if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$id_agenda_kegiatan = $keterangan = $tanggal_mulai = $tanggal_selesai = $kategori = $foto = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_agenda_kegiatan   = trim($_POST['id_agenda_kegiatan'] ?? '');
    $keterangan           = trim($_POST['keterangan'] ?? '');
    $tanggal_mulai        = trim($_POST['tanggal_mulai'] ?? '');
    $tanggal_selesai      = trim($_POST['tanggal_selesai'] ?? '');
    $kategori             = trim($_POST['kategori'] ?? '');

    if ($id_agenda_kegiatan === '' || $keterangan === '' || $tanggal_mulai === '' || $kategori === '' || empty($_FILES['foto']['name'])) {
        $err = 'Semua field wajib diisi.';
    } else {
        $upload_dir = __DIR__ . '/images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = basename($_FILES['foto']['name']);
        $file_tmp = $_FILES['foto']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];

        if (!in_array($file_ext, $allowed_ext)) {
            $err = 'Format file foto harus jpg, jpeg, png, gif, atau bmp.';
        } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            $err = 'Ukuran foto harus maksimal 2MB.';
        } else {
            $new_filename = uniqid('galeri_') . '.' . $file_ext;
            $destination = $upload_dir . $new_filename;

            if (move_uploaded_file($file_tmp, $destination)) {
                $foto = 'images/' . $new_filename;

                try {
                    qparams(
                        'INSERT INTO "agenda_kegiatan" ("id_agenda_kegiatan", "keterangan", "tanggal_mulai", "tanggal_selesai", "kategori", "foto") VALUES ($1, $2, $3, $4, $5, $6)',
                        [$id_agenda_kegiatan, $keterangan, $tanggal_mulai, $tanggal_selesai, $kategori, $foto]
                    );
                    header('Location: galeri.php');
                    exit;
                } catch (Throwable $e) {
                    $err = $e->getMessage();
                    if (file_exists($destination)) unlink($destination);
                }
            } else {
                $err = 'Gagal upload foto.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Galeri</title>
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
                        Tambah Galeri
                    </div>
                    <form class="form-user-box" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="desc-form">
                            Isi data galeri di bawah dengan lengkap dan benar.<br>
                            Upload gambar maksimal 2MB (format: jpg/png/gif/bmp).
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="id_agenda_kegiatan" class="user-form-label">Id Agenda Kegiatan</label>
                            <input type="text" name="id_agenda_kegiatan" id="id_agenda_kegiatan" class="user-form-input" value="<?= htmlspecialchars($id_agenda_kegiatan) ?>" required autocomplete="off" placeholder="Masukkan Id Agenda Kegiatan">
                        </div>

                        <div class="form-group">
                            <label for="keterangan" class="user-form-label">Keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" class="user-form-input" value="<?= htmlspecialchars($keterangan) ?>" required autocomplete="off" placeholder="Masukkan Keterangan">
                        </div>

                        <div class="form-group">
                            <label for="tanggal_mulai" class="user-form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="user-form-input" value="<?= htmlspecialchars($tanggal_mulai) ?>" required autocomplete="off" placeholder="Masukkan Tanggal Mulai">
                        </div>

                        <div class="form-group">
                            <label for="tanggal_selesai" class="user-form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="user-form-input" value="<?= htmlspecialchars($tanggal_selesai) ?>" required autocomplete="off" placeholder="Masukkan Tanggal Selesai">
                        </div>

                        <div class="form-group">
                            <label for="kategori" class="user-form-label">Kategori</label>
                            <select name="kategori" id="kategori" class="user-form-input" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Agenda" <?= ($kategori === "Agenda" ? 'selected' : '') ?>>Agenda</option>
                                <option value="Kegiatan" <?= ($kategori === "Kegiatan" ? 'selected' : '') ?>>Kegiatan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="foto" class="user-form-label">Foto</label>
                            <input type="file" name="foto" id="foto" class="user-form-input" required accept=".jpg,.jpeg,.png,.gif,.bmp">
                        </div>

                        <div class="form-btn-bar" style="margin-top:32px;">
                            <button class="btn-user-primary" type="submit">
                                <span class="iconify" data-icon="mdi:content-save"></span> Simpan
                            </button>
                            <a class="btn-user-warning" href="struktur_organisasi.php">
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