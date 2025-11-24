<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$id = (string)($_GET['id'] ?? $_GET['id_pengguna'] ?? '');

if ($id === '') {
    http_response_code(400);
    exit('Name tidak valid.');
}

try {
    $res = qparams('SELECT "id_misi", "isi_misi" FROM "misi" WHERE "id_misi"=$1', [$id]);
    $row = pg_fetch_assoc($res);
    if (!$row) {
        http_response_code(404);
        exit('Data tidak ditemukan.');
    }
} catch (Throwable $e) {
    exit('Error: ' . htmlspecialchars($e->getMessage()));
}

$Id_Misi = $row['id_misi'];
$Isi_Misi = $row['isi_misi'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Id_Misi   = trim($_POST['id_misi'] ?? '');
    $Isi_Misi      = trim($_POST['isi_misi'] ?? '');

    if ($Id_Misi === '' || $Isi_Misi === '') {
        $err = 'Semua field wajib diisi.';
    } else {
        try {
            qparams(
                'UPDATE "misi"
                   SET "isi_misi"=$1
                 WHERE "id_misi"=$2',
                [$Isi_Misi, $id]
            );
            header('Location: misi.php');
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
    <title>Ubah Misi</title>
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
                        Ubah Misi
                    </div>
                    <form class="form-user-box" method="post" autocomplete="off">
                        <div class="desc-form">
                            Ubah data misi di bawah sesuai kebutuhan.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="id_misi" class="user-form-label">Id Misi</label>
                            <input type="text" name="id_misi" id="id_misi" class="user-form-input" value="<?= htmlspecialchars($Id_Misi) ?>" required autocomplete="off" placeholder="Masukkan Id Misi">
                        </div>

                        <div class="form-group">
                            <label for="isi_misi" class="user-form-label">Isi Misi</label>
                            <textarea name="isi_misi" id="isi_misi" class="user-form-input" rows="8" required autocomplete="off" placeholder="Masukkan isi"><?= htmlspecialchars($Isi_Misi) ?></textarea>
                        </div>

                        <div class="form-btn-bar" style="margin-top:32px;">
                            <button class="btn-user-primary" type="submit">
                                <span class="iconify" data-icon="mdi:content-save"></span> Simpan
                            </button>
                            <a class="btn-user-warning" href="misi.php">
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