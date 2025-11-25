<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$res = q('SELECT "id_konsultasi", "nama_konsultasi", "nim_konsultasi", "isi_konsultasi", "tanggal_konsultasi", "email_konsultasi", "no_wa_konsultasi", "status" FROM "v_konsultasi" ORDER BY "id_konsultasi" ASC');
$rows = pg_fetch_all($res) ?: [];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Konsultasi</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="layout">
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="main-table-title">Daftar Konsultasi</div>
            <p>
                <a class="btn btn-primary" href="konsultasi_create.php" style="margin-left: 0;">+ Tambah Konsultasi</a>
            </p>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Isi Konsultasi</th>
                        <th>Tanggal</th>
                        <th>Email</th>
                        <th>No. WA</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$rows): ?>
                        <tr>
                            <td colspan="9">Belum ada konsultasi.</td>
                        </tr>
                    <?php else: foreach ($rows as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['id_konsultasi'] ?? '') ?></td>
                            <td><?= htmlspecialchars($r['nama_konsultasi'] ?? '') ?></td>
                            <td><?= htmlspecialchars($r['nim_konsultasi'] ?? '') ?></td>
                            <td><?= htmlspecialchars($r['isi_konsultasi'] ?? '') ?></td>
                            <td><?= htmlspecialchars($r['tanggal_konsultasi'] ?? '') ?></td>
                            <td><?= htmlspecialchars($r['email_konsultasi'] ?? '') ?></td>
                            <td><?= htmlspecialchars($r['no_wa_konsultasi'] ?? '') ?></td>
                            <td><?= htmlspecialchars($r['status'] ?? '') ?></td>
                            <td>
                                <a class="btn btn-warning" href="konsultasi_update.php?id=<?= urlencode($r['id_konsultasi']) ?>">Ubah</a>
                                <a href="#" class="btn btn-danger" onclick="if(confirm('Hapus data ini?')) { document.getElementById('deleteForm<?= $r['id_konsultasi'] ?>').submit(); }">Hapus</a>
                                <form id="deleteForm<?= $r['id_konsultasi'] ?>" action="konsultasi_delete.php" method="post" style="display:none;">
                                    <input type="hidden" name="id_konsultasi" value="<?= htmlspecialchars($r['id_konsultasi']) ?>">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach;
                    endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>