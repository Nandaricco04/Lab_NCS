<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$res = q('SELECT "id_detail_pengabdian", "id_pengabdian", "ketua", "prodi", "judul", "skema" FROM "v_detail_pengabdian" ORDER BY "id_detail_pengabdian" ASC');
$rows = pg_fetch_all($res) ?: [];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Detail Pengabdian</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
     <div class="layout">
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="main-table-title">Daftar Detail Pengabdian</div>
            <p>
                <a class="btn btn-primary" href="detail_pengabdian_create.php" style="margin-left: 0;">+ Tambah Detail Pengabdian</a>
            </p>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID Detail</th>
                        <th>ID Pengabdian</th>
                        <th>Ketua</th>
                        <th>Prodi</th>
                        <th>Judul</th>
                        <th>Skema</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="7">Belum ada data detail pengabdian.</td>
                    </tr>
                <?php else: foreach ($rows as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['id_detail_pengabdian'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['id_pengabdian'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['ketua'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['prodi'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['judul'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['skema'] ?? '') ?></td>
                        <td>
                            <a class="btn btn-warning" href="detail_pengabdian_update.php?id=<?= urlencode($r['id_detail_pengabdian']) ?>">Ubah</a>
                            <a href="#" class="btn btn-danger" onclick="if(confirm('Hapus data ini?')) { document.getElementById('deleteForm<?= $r['id_detail_pengabdian'] ?>').submit(); }">Hapus</a>
                            <form id="deleteForm<?= $r['id_detail_pengabdian'] ?>" action="detail_pengabdian_delete.php" method="post" style="display:none;">
                                <input type="hidden" name="id_detail_pengabdian" value="<?= htmlspecialchars($r['id_detail_pengabdian']) ?>">
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