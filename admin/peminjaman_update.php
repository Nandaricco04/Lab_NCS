<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$id = (string)($_GET['id'] ?? $_GET['id_peminjaman'] ?? '');

if ($id === '') {
    http_response_code(400);
    exit('ID tidak valid.');
}

try {
    $res = qparams('SELECT "id_sarana_prasarana", "nama_peminjam", "nim_peminjam", "email_peminjam", "no_wa_peminjam", "jumlah_pinjam", "tanggal_peminjaman", "tanggal_pengembalian", "status" FROM "peminjaman" WHERE "id_peminjaman"=$1', [$id]);
    $row = pg_fetch_assoc($res);
    if (!$row) {
        http_response_code(404);
        exit('Data tidak ditemukan.');
    }
} catch (Throwable $e) {
    exit('Error: ' . htmlspecialchars($e->getMessage()));
}

$id_sarana_prasarana = $row['id_sarana_prasarana'];
$nama_peminjam = $row['nama_peminjam'];
$nim_peminjam = $row['nim_peminjam'];
$email_peminjam = $row['email_peminjam'];
$no_wa_peminjam = $row['no_wa_peminjam'];
$jumlah_pinjam = $row['jumlah_pinjam'];
$tanggal_peminjaman = $row['tanggal_peminjaman'];
$tanggal_pengembalian = $row['tanggal_pengembalian'];
$status = $row['status'];

$saranaOptions = [];
$resSarana = q('SELECT "id_sarana_prasarana", "judul" FROM "sarana_prasarana" ORDER BY "judul" ASC');
while ($rowOpt = pg_fetch_assoc($resSarana)) {
    $saranaOptions[] = $rowOpt;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_sarana_prasarana = trim($_POST['id_sarana_prasarana'] ?? '');
    $nama_peminjam = trim($_POST['nama_peminjam'] ?? '');
    $nim_peminjam = trim($_POST['nim_peminjam'] ?? '');
    $email_peminjam = trim($_POST['email_peminjam'] ?? '');
    $no_wa_peminjam = trim($_POST['no_wa_peminjam'] ?? '');
    $jumlah_pinjam = trim($_POST['jumlah_pinjam'] ?? '');
    $tanggal_peminjaman = trim($_POST['tanggal_peminjaman'] ?? '');
    $tanggal_pengembalian = trim($_POST['tanggal_pengembalian'] ?? '');
    $status = trim($_POST['status'] ?? '');

    if (
        $id_sarana_prasarana === '' || $nama_peminjam === '' || $nim_peminjam === '' ||
        $email_peminjam === '' || $no_wa_peminjam === '' || $jumlah_pinjam === '' ||
        $tanggal_peminjaman === '' || $tanggal_pengembalian === '' || $status === ''
    ) {
        $err = 'Semua field wajib diisi.';
    } else {
        try {
            qparams(
                'UPDATE "peminjaman"
                SET "nama_peminjam"=$1, "nim_peminjam"=$2,
                    "email_peminjam"=$3, "no_wa_peminjam"=$4, "jumlah_pinjam"=$5,
                    "tanggal_peminjaman"=$6, "tanggal_pengembalian"=$7, "status"=$8
                WHERE "id_peminjaman"=$9',
                [
                    $nama_peminjam, $nim_peminjam, $email_peminjam,
                    $no_wa_peminjam, $jumlah_pinjam, $tanggal_peminjaman, $tanggal_pengembalian, $status, $id
                ]
            );
            header('Location: peminjaman.php');
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
    <title>Ubah Peminjaman</title>
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
                        Ubah Peminjaman
                    </div>
                    <form class="form-user-box" method="post" autocomplete="off">
                        <div class="desc-form">
                            Ubah data peminjaman di bawah sesuai kebutuhan.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="id_sarana_prasarana" class="user-form-label">Sarana/Prasarana</label>
                            <select name="id_sarana_prasarana" id="id_sarana_prasarana" class="user-form-input" required>
                                <option value="">-- Pilih Sarana/Prasarana --</option>
                                <?php foreach ($saranaOptions as $opt): ?>
                                    <option value="<?= htmlspecialchars($opt['id_sarana_prasarana']) ?>"
                                        <?= $id_sarana_prasarana == $opt['id_sarana_prasarana'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($opt['judul']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nama_peminjam" class="user-form-label">Nama Peminjam</label>
                            <input type="text" name="nama_peminjam" id="nama_peminjam" class="user-form-input" value="<?= htmlspecialchars($nama_peminjam) ?>" required autocomplete="off" placeholder="Masukkan Nama Peminjam">
                        </div>
                        <div class="form-group">
                            <label for="nim_peminjam" class="user-form-label">NIM Peminjam</label>
                            <input type="text" name="nim_peminjam" id="nim_peminjam" class="user-form-input" value="<?= htmlspecialchars($nim_peminjam) ?>" required autocomplete="off" placeholder="Masukkan NIM">
                        </div>
                        <div class="form-group">
                            <label for="email_peminjam" class="user-form-label">Email</label>
                            <input type="email" name="email_peminjam" id="email_peminjam" class="user-form-input" value="<?= htmlspecialchars($email_peminjam) ?>" required autocomplete="off" placeholder="Masukkan Email">
                        </div>
                        <div class="form-group">
                            <label for="no_wa_peminjam" class="user-form-label">No. Whatsapp</label>
                            <input type="text" name="no_wa_peminjam" id="no_wa_peminjam" class="user-form-input" value="<?= htmlspecialchars($no_wa_peminjam) ?>" required autocomplete="off" placeholder="Masukkan No. Whatsapp">
                        </div>
                        <div class="form-group">
                            <label for="jumlah_pinjam" class="user-form-label">Jumlah Pinjam</label>
                            <input type="number" name="jumlah_pinjam" id="jumlah_pinjam" class="user-form-input" value="<?= htmlspecialchars($jumlah_pinjam) ?>" required autocomplete="off" min="1" placeholder="Masukkan Jumlah">
                        </div>
                        <div class="form-group">
                            <label for="tanggal_peminjaman" class="user-form-label">Tanggal Peminjaman</label>
                            <input type="date" name="tanggal_peminjaman" id="tanggal_peminjaman" class="user-form-input" value="<?= htmlspecialchars($tanggal_peminjaman) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_pengembalian" class="user-form-label">Tanggal Pengembalian</label>
                            <input type="date" name="tanggal_pengembalian" id="tanggal_pengembalian" class="user-form-input" value="<?= htmlspecialchars($tanggal_pengembalian) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="status" class="user-form-label">Status</label>
                            <select name="status" id="status" class="user-form-input" required>
                                <option value="Proses" <?= $status === 'Proses' ? 'selected' : '' ?>>Proses</option>
                                <option value="Ambil" <?= $status === 'Ambil' ? 'selected' : '' ?>>Ambil</option>
                                <option value="Selesai" <?= $status === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                            </select>
                        </div>
                        <div class="form-btn-bar" style="margin-top:32px;">
                            <button class="btn-user-primary" type="submit">
                                <span class="iconify" data-icon="mdi:content-save"></span> Simpan
                            </button>
                            <a class="btn-user-warning" href="peminjaman.php">
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