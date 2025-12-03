<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/koneksi.php';

// Ambil data langsung dari view v_detail_pengabdian yang sudah ada tahun_pengabdian dan judul_pengabdian.
$res = q('SELECT "id_detail_pengabdian", "id_pengabdian", "prodi", "judul_detail", "skema", "judul_pengabdian", "tahun_pengabdian"
          FROM "v_detail_pengabdian"
          ORDER BY "id_detail_pengabdian" ASC');
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
                        <th>Tahun Pengabdian</th>
                        <th>Judul Pengabdian</th>
                        <th>Prodi</th>
                        <th>Judul Detail</th>
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
                        <td><?= htmlspecialchars($r['tahun_pengabdian'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['judul_pengabdian'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['prodi'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['judul_detail'] ?? '') ?></td>
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