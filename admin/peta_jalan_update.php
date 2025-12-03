<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$id = (string)($_GET['id'] ?? $_GET['id_peta_jalan'] ?? '');

if ($id === '') {
    http_response_code(400);
    exit('ID tidak valid.');
}

try {
    $res = qparams('SELECT "judul", "tahun", "file_path" FROM "peta_jalan" WHERE "id_peta_jalan"=$1', [$id]);
    $row = pg_fetch_assoc($res);
    if (!$row) {
        http_response_code(404);
        exit('Data tidak ditemukan.');
    }
} catch (Throwable $e) {
    exit('Error: ' . htmlspecialchars($e->getMessage()));
}

$Judul = $row['judul'];
$Tahun = $row['tahun'];
$file_path = $row['file_path'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Judul = trim($_POST['judul'] ?? '');
    $Tahun = trim($_POST['tahun'] ?? '');
    $file_path_baru = $file_path;

    if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] == UPLOAD_ERR_OK) {
        $allowed_ext = ['pdf'];
        $upload_dir = __DIR__ . '/files/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $tmp_name = $_FILES['file_path']['tmp_name'];
        $nama_file = basename($_FILES['file_path']['name']);
        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_ext)) {
            $err = 'File harus berformat PDF.';
        } elseif ($_FILES['file_path']['size'] > 2 * 1024 * 1024) {
            $err = 'Ukuran file maksimal 2MB.';
        } else {
            $nama_file_unik = 'peta_jalan_' . time() . '_' . uniqid() . '.' . $ext;
            $dest_path = $upload_dir . $nama_file_unik;

            if (move_uploaded_file($tmp_name, $dest_path)) {
                $file_path_baru = 'files/' . $nama_file_unik;
                if ($file_path && file_exists(__DIR__ . '/' . $file_path)) {
                    @unlink(__DIR__ . '/' . $file_path);
                }
            } else {
                $err = 'Gagal upload file.';
            }
        }
    }

    if ($Judul === '' || $Tahun === '') {
        $err = 'Semua field wajib diisi.';
    } else {
        if (!$err) {
            try {
                qparams(
                    'UPDATE "peta_jalan"
                       SET "judul"=$1, "tahun"=$2, "file_path"=$3
                     WHERE "id_peta_jalan"=$4',
                    [$Judul, $Tahun, $file_path_baru, $id]
                );
                header('Location: peta_jalan.php');
                exit;
            } catch (Throwable $e) {
                $err = $e->getMessage();
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
    <title>Ubah Peta Jalan</title>
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
                        Ubah Peta Jalan
                    </div>
                    <form class="form-user-box" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="desc-form">
                            Ubah data peta jalan di bawah sesuai kebutuhan.<br>
                            File hanya menerima PDF maksimal 2MB.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="judul" class="user-form-label">Judul</label>
                            <input type="text" name="judul" id="judul" class="user-form-input" value="<?= htmlspecialchars($Judul) ?>" required autocomplete="off" placeholder="Masukkan Judul">
                        </div>

                        <div class="form-group">
                            <label for="tahun" class="user-form-label">Tahun</label>
                        <input type="date" name="tahun" id="tahun" class="user-form-input" value="<?= htmlspecialchars($Tahun) ?>" required autocomplete="off" placeholder="Masukkan Tahun">
                        </div>

                        <div class="form-group">
                            <label for="file_path" class="user-form-label">File PDF</label>
                            <input type="file" name="file_path" id="file_path" class="user-form-input" accept=".pdf">
                            <?php if ($file_path): ?>
                                <div style="margin-top:8px;">
                                    <a href="<?= htmlspecialchars($file_path) ?>" target="_blank"><?= htmlspecialchars(basename($file_path)) ?></a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-btn-bar" style="margin-top:32px;">
                            <button class="btn-user-primary" type="submit">
                                <span class="iconify" data-icon="mdi:content-save"></span> Simpan
                            </button>
                            <a class="btn-user-warning" href="peta_jalan.php">
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
