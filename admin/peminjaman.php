<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$resAlat = q('SELECT "id_sarana_prasarana", "judul" FROM "sarana_prasarana"');
$alatRows = pg_fetch_all($resAlat) ?: [];
$namaAlatMap = [];
foreach ($alatRows as $alat) {
    $namaAlatMap[$alat['id_sarana_prasarana']] = $alat['judul'];
}

$res = q('SELECT "id_peminjaman", "id_sarana_prasarana", "nama_peminjam", "nim_peminjam", "email_peminjam", "no_wa_peminjam", "jumlah_pinjam", "tanggal_peminjaman", "tanggal_pengembalian", "status" FROM "v_peminjaman" ORDER BY "id_peminjaman" ASC');
$rows = pg_fetch_all($res) ?: [];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Peminjaman</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="layout">
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="main-table-title">Daftar Peminjaman</div>
            <p>
                <a class="btn btn-primary" href="peminjaman_create.php" style="margin-left: 0;">+ Tambah Peminjaman</a>
            </p>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Alat</th>
                        <th>Nama Peminjam</th>
                        <th>NIM</th>
                        <th>Email</th>
                        <th>No. WA</th>
                        <th>Jumlah Pinjam</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$rows): ?>
                        <tr>
                            <td colspan="11">Belum ada peminjaman.</td>
                        </tr>
                    <?php else: foreach ($rows as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['id_peminjaman'] ?? '') ?></td>
                            <td>
                                <?= htmlspecialchars($namaAlatMap[$r['id_sarana_prasarana']] ?? $r['id_sarana_prasarana']) ?>
                            </td>
                            <td><?= htmlspecialchars($r['nama_peminjam'] ?? '') ?></td>
                            <td><?= htmlspecialchars($r['nim_peminjam'] ?? '') ?></td>
                            <td><?= htmlspecialchars($r['email_peminjam'] ?? '') ?></td>
                            <td><?= htmlspecialchars($r['no_wa_peminjam'] ?? '') ?></td>
                            <td><?= htmlspecialchars($r['jumlah_pinjam'] ?? '') ?></td>
                            <td><?= htmlspecialchars($r['tanggal_peminjaman'] ?? '') ?></td>
                            <td><?= htmlspecialchars($r['tanggal_pengembalian'] ?? '') ?></td>
                            <td><?= htmlspecialchars(ucfirst(strtolower($r['status'] ?? ''))) ?></td>
                            <td>
                                <a class="btn btn-warning" href="peminjaman_update.php?id=<?= urlencode($r['id_peminjaman']) ?>">Ubah</a>
                                <a href="#" class="btn btn-danger" onclick="if(confirm('Hapus data ini?')) { document.getElementById('deleteForm<?= $r['id_peminjaman'] ?>').submit(); }">Hapus</a>
                                <form id="deleteForm<?= $r['id_peminjaman'] ?>" action="peminjaman_delete.php" method="post" style="display:none;">
                                    <input type="hidden" name="id_peminjaman" value="<?= htmlspecialchars($r['id_peminjaman']) ?>">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>