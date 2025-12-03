<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$id = (string)($_GET['id'] ?? $_GET['id_detail_pengabdian'] ?? '');

if ($id === '') {
    http_response_code(400);
    exit('ID tidak valid.');
}

try {
    $res = qparams('SELECT "id_pengabdian", "id_pengelola", "prodi", "judul", "skema" FROM "detail_pengabdian" WHERE "id_detail_pengabdian"=$1', [$id]);
    $row = pg_fetch_assoc($res);
    if (!$row) {
        http_response_code(404);
        exit('Data tidak ditemukan.');
    }
} catch (Throwable $e) {
    exit('Error: ' . htmlspecialchars($e->getMessage()));
}

$id_pengabdian      = $row['id_pengabdian'];
$id_pengelola       = $row['id_pengelola'];
$prodi              = $row['prodi'];
$judul              = $row['judul'];
$skema              = $row['skema'];

$pengabdianOptions = [];
$resPengabdian = q('SELECT "id_pengabdian", "tahun", "judul" FROM "pengabdian" ORDER BY "tahun" DESC');
while ($rowOpt = pg_fetch_assoc($resPengabdian)) {
    $pengabdianOptions[] = $rowOpt;
}

$ketuaOptions = [];
$resKetua = qparams('SELECT "id_pengelola", "nama" FROM "struktur_organisasi" WHERE "posisi" = $1', ['Kepala Lab']);
while ($rowKetua = pg_fetch_assoc($resKetua)) {
    $ketuaOptions[] = $rowKetua;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pengabdian        = trim($_POST['id_pengabdian'] ?? '');
    $id_pengelola         = trim($_POST['id_pengelola'] ?? '');
    $prodi                = trim($_POST['prodi'] ?? '');
    $judul                = trim($_POST['judul'] ?? '');
    $skema                = trim($_POST['skema'] ?? '');

    if ($id_pengabdian === '' || $id_pengelola === '' || $prodi === '' || $judul === '' || $skema === '') {
        $err = 'Semua field wajib diisi.';
    } else {
        try {
            qparams(
                'UPDATE "detail_pengabdian" SET "id_pengabdian"=$1, "id_pengelola"=$2, "prodi"=$3, "judul"=$4, "skema"=$5 WHERE "id_detail_pengabdian"=$6',
                [$id_pengabdian, $id_pengelola, $prodi, $judul, $skema, $id_detail_pengabdian]
            );
            header('Location: detail_pengabdian.php');
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
    <title>Ubah Detail Pengabdian</title>
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
                        Ubah Detail Pengabdian
                    </div>
                    <form class="form-user-box" method="post" autocomplete="off">
                        <div class="desc-form">
                            Ubah data detail pengabdian di bawah sesuai kebutuhan.<br>
                            Pilih tahun dari daftar pengabdian yang tersedia.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="id_pengabdian" class="user-form-label">Tahun & Judul Pengabdian</label>
                            <select name="id_pengabdian" id="id_pengabdian" class="user-form-input" required>
                                <option value="">-- Pilih Pengabdian --</option>
                                <?php foreach ($pengabdianOptions as $opt): ?>
                                    <option value="<?= htmlspecialchars($opt['id_pengabdian']) ?>"
                                        <?= $id_pengabdian == $opt['id_pengabdian'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($opt['tahun']) ?> - <?= htmlspecialchars($opt['judul']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_pengelola" class="user-form-label">Ketua</label>
                            <select name="id_pengelola" id="id_pengelola" class="user-form-input" required>
                                <option value="">-- Pilih Ketua (Kepala Lab) --</option>
                                <?php foreach ($ketuaOptions as $opt): ?>
                                    <option value="<?= htmlspecialchars($opt['id_pengelola']) ?>"
                                        <?= $id_pengelola == $opt['id_pengelola'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($opt['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="prodi" class="user-form-label">Prodi</label>
                            <input type="text" name="prodi" id="prodi" class="user-form-input" value="<?= htmlspecialchars($prodi) ?>" required autocomplete="off" placeholder="Masukkan Prodi">
                        </div>
                        <div class="form-group">
                            <label for="judul" class="user-form-label">Judul</label>
                            <textarea name="judul" id="judul" class="user-form-input" required autocomplete="off" placeholder="Masukkan Judul"><?= htmlspecialchars($judul) ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="skema" class="user-form-label">Skema</label>
                            <input type="text" name="skema" id="skema" class="user-form-input" value="<?= htmlspecialchars($skema) ?>" required autocomplete="off" placeholder="Masukkan Skema">
                        </div>
                        <div class="form-btn-bar" style="margin-top:32px;">
                            <button class="btn-user-primary" type="submit">
                                <span class="iconify" data-icon="mdi:content-save"></span> Simpan
                            </button>
                            <a class="btn-user-warning" href="detail_pengabdian.php">
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