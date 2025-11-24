<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$id = (string)($_GET['id'] ?? $_GET['id_agenda_kegiatan'] ?? '');

if ($id === '') {
    http_response_code(400);
    exit('ID tidak valid.');
}

try {
    $res = qparams('SELECT "id_agenda_kegiatan", "keterangan", "tanggal_mulai", "tanggal_selesai", "kategori", "foto" FROM "agenda_kegiatan" WHERE "id_agenda_kegiatan"=$1', [$id]);
    $row = pg_fetch_assoc($res);
    if (!$row) {
        http_response_code(404);
        exit('Data tidak ditemukan.');
    }
} catch (Throwable $e) {
    exit('Error: ' . htmlspecialchars($e->getMessage()));
}

$Id_Agenda_Kegiatan = $row['id_agenda_kegiatan'];
$Keterangan = $row['keterangan'];
$Tanggal_Mulai = $row['tanggal_mulai'];
$Tanggal_Selesai = $row['tanggal_selesai'];
$Kategori = $row['kategori'];
$foto = $row['foto'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Id_Agenda_Kegiatan = trim($_POST['id_agenda_kegiatan'] ?? '');
    $Keterangan = trim($_POST['keterangan'] ?? '');
    $Tanggal_Mulai = trim($_POST['tanggal_mulai'] ?? '');
    $Tanggal_Selesai = trim($_POST['tanggal_selesai'] ?? '');
    $Kategori = trim($_POST['kategori'] ?? '');
    $foto_baru = $foto;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $img_dir = 'images/';
        if (!is_dir($img_dir)) {
            mkdir($img_dir, 0755, true);
        }
        $tmp_name = $_FILES['foto']['tmp_name'];
        $nama_file = basename($_FILES['foto']['name']);
        $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
        $nama_file_unik = 'galeri_' . time() . '_' . uniqid() . '.' . $ext;
        $dest_path = $img_dir . $nama_file_unik;

        if (move_uploaded_file($tmp_name, $dest_path)) {
            $foto_baru = $dest_path;
        } else {
            $err = 'Gagal mengupload gambar.';
        }
    }

    if ($Id_Agenda_Kegiatan === '' || $Keterangan === '' || $Tanggal_Mulai === '' || $Tanggal_Selesai === '' || $Kategori === '' || $foto_baru === '') {
        $err = 'Semua field wajib diisi.';
    } else {
        try {
            qparams(
                'UPDATE "agenda_kegiatan"
                   SET "keterangan"=$1, "tanggal_mulai"=$2, "tanggal_selesai"=$3, "kategori"=$4, "foto"=$5
                 WHERE "id_agenda_kegiatan"=$6',
                [$Keterangan, $Tanggal_Mulai, $Tanggal_Selesai, $Kategori, $foto_baru, $id]
            );
            header('Location: galeri.php');
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
    <title>Ubah Galeri</title>
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
                        Ubah Galeri
                    </div>
                    <form class="form-user-box" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="desc-form">
                            Ubah data galeri di bawah sesuai kebutuhan.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="id_agenda_kegiatan" class="user-form-label">Id Agenda Kegiatan</label>
                            <input type="text" name="id_agenda_kegiatan" id="id_agenda_kegiatan" class="user-form-input" value="<?= htmlspecialchars($Id_Agenda_Kegiatan) ?>" required autocomplete="off" placeholder="Masukkan Id Agenda Kegiatan">
                        </div>

                        <div class="form-group">
                            <label for="keterangan" class="user-form-label">Keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" class="user-form-input" value="<?= htmlspecialchars($Keterangan) ?>" required autocomplete="off" placeholder="Masukkan Keterangan">
                        </div>

                        <div class="form-group">
                            <label for="tanggal_mulai" class="user-form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="user-form-input" value="<?= htmlspecialchars($Tanggal_Mulai) ?>" required autocomplete="off" placeholder="Masukkan Tanggal Mulai">
                        </div>

                        <div class="form-group">
                            <label for="tanggal_selesai" class="user-form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="user-form-input" value="<?= htmlspecialchars($Tanggal_Selesai) ?>" required autocomplete="off" placeholder="Masukkan Tanggal Selesai">
                        </div>

                        <div class="form-group">
                            <label for="kategori" class="user-form-label">Kategori</label>
                            <select name="kategori" id="kategori" class="user-form-input" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Agenda" <?= ($Kategori === "Agenda" ? 'selected' : '') ?>>Agenda</option>
                                <option value="Kegiatan" <?= ($Kategori === "Kegiatan" ? 'selected' : '') ?>>Kegiatan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="foto" class="user-form-label">Foto</label>
                            <input type="file" name="foto" id="foto" class="user-form-input" accept="images/*">
                            <?php if ($foto): ?>
                                <div style="margin-top:8px;">
                                    <img src="<?= htmlspecialchars($foto) ?>" alt="Foto saat ini" style="max-width:200px;max-height:200px;">
                                </div>
                            <?php endif; ?>
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