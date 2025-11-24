<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$id = (string)($_GET['id'] ?? $_GET['id_sarana_prasarana'] ?? '');

if ($id === '') {
    http_response_code(400);
    exit('ID tidak valid.');
}

try {
    $res = qparams('SELECT "id_sarana_prasarana", "gambar_path", "judul", "sub_judul", "jumlah_alat" FROM "sarana_prasarana" WHERE "id_sarana_prasarana"=$1', [$id]);
    $row = pg_fetch_assoc($res);
    if (!$row) {
        http_response_code(404);
        exit('Data tidak ditemukan.');
    }
} catch (Throwable $e) {
    exit('Error: ' . htmlspecialchars($e->getMessage()));
}

$Id_Sarana_Prasarana = $row['id_sarana_prasarana'];
$Gambar_Path = $row['gambar_path'];
$Judul = $row['judul'];
$Sub_Judul = $row['sub_judul'];
$Jumlah_Alat = $row['jumlah_alat'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Id_Sarana_Prasarana = trim($_POST['id_sarana_prasarana'] ?? '');
    $gambar_path_baru = $Gambar_Path;
    $Judul = trim($_POST['judul'] ?? '');
    $Sub_Judul = trim($_POST['sub_judul'] ?? '');
    $Jumlah_Alat = trim($_POST['jumlah_alat'] ?? '');
    
    if (isset($_FILES['gambar_path']) && $_FILES['gambar_path']['error'] == UPLOAD_ERR_OK) {
        $img_dir = 'images/';
        if (!is_dir($img_dir)) {
            mkdir($img_dir, 0755, true);
        }
        $tmp_name = $_FILES['gambar_path']['tmp_name'];
        $nama_file = basename($_FILES['gambar_path']['name']);
        $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
        $nama_file_unik = 'sarana_prasarana_' . time() . '_' . uniqid() . '.' . $ext;
        $dest_path = $img_dir . $nama_file_unik;

        if (move_uploaded_file($tmp_name, $dest_path)) {
            $gambar_path_baru = $dest_path;
        } else {
            $err = 'Gagal mengupload gambar.';
        }
    }

    if ($Id_Sarana_Prasarana === '' || $Judul === '' || $Sub_Judul === '' || $Jumlah_Alat === '' || $gambar_path_baru === '') {
        $err = 'Semua field wajib diisi.';
    } else {
        try {
            qparams(
                'UPDATE "sarana_prasarana"
                   SET "gambar_path"=$1, "judul"=$2, "sub_judul"=$3, "jumlah_alat"=$4
                 WHERE "id_sarana_prasarana"=$5',
                [$gambar_path_baru, $Judul, $Sub_Judul, $Jumlah_Alat, $id]
            );
            header('Location: sarana.php');
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
        <title>Ubah Sarana Prasarana</title>
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
                        Ubah Sarana Prasarana
                    </div>
                    <form class="form-user-box" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="desc-form">
                            Ubah data sarana prasarana di bawah sesuai kebutuhan.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="id_sarana_prasarana" class="user-form-label">Id Sarana Prasarana</label>
                            <input type="text" name="id_sarana_prasarana" id="id_sarana_prasarana" class="user-form-input" value="<?= htmlspecialchars($Id_Sarana_Prasarana) ?>" required autocomplete="off" placeholder="Masukkan Id Sarana Prasarana">
                        </div>

                        <div class="form-group">
                            <label for="gambar_path" class="user-form-label">Gambar</label>
                            <input type="file" name="gambar_path" id="gambar_path" class="user-form-input" accept="image/*">
                            <?php if ($Gambar_Path): ?>
                                <div style="margin-top:8px;">
                                    <img src="<?= htmlspecialchars($Gambar_Path) ?>" alt="Gambar saat ini" style="max-width:200px;max-height:200px;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="judul" class="user-form-label">Judul</label>
                            <input type="text" name="judul" id="judul" class="user-form-input" value="<?= htmlspecialchars($Judul) ?>" required autocomplete="off" placeholder="Masukkan Judul">
                        </div>

                        <div class="form-group">
                            <label for="sub_judul" class="user-form-label">Sub Judul</label>
                            <input type="text" name="sub_judul" id="sub_judul" class="user-form-input" value="<?= htmlspecialchars($Sub_Judul) ?>" required autocomplete="off" placeholder="Masukkan Sub Judul">
                        </div>

                        <div class="form-group">
                            <label for="jumlah_alat" class="user-form-label">Jumlah Alat</label>
                            <input type="text" name="jumlah_alat" id="jumlah_alat" class="user-form-input" value="<?= htmlspecialchars($Jumlah_Alat) ?>" required autocomplete="off" placeholder="Masukkan Jumlah Alat">
                        </div>
                                
                        <div class="form-btn-bar" style="margin-top:32px;">
                            <button class="btn-user-primary" type="submit">
                                <span class="iconify" data-icon="mdi:content-save"></span> Simpan
                            </button>
                            <a class="btn-user-warning" href="sarana.php">
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