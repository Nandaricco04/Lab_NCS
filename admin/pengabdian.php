<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$res = q('SELECT "id_pengabdian", "tahun", "judul" FROM "v_pengabdian" ORDER BY "id_pengabdian" ASC');
$rows = pg_fetch_all($res) ?: [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Pengabdian</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="layout">
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="main-table-title">Daftar Pengabdian</div>
            <p>
                <a class="btn btn-primary" href="pengabdian_create.php" style="margin-left: 0;">+ Tambah Pengabdian</a>
            </p>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tahun</th>
                        <th>Judul</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$rows): ?>
                        <tr>
                            <td colspan="4">Belum ada pengabdian.</td>
                        </tr>
                        <?php else: foreach ($rows as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r['id_pengabdian'] ?? '') ?></td>
                                <td><?= htmlspecialchars($r['tahun'] ?? '') ?></td>
                                <td><?= htmlspecialchars($r['judul'] ?? '') ?></td>
                                <td>
                                    <a class="btn btn-warning" style="margin: 2px;" href="pengabdian_update.php?id=<?= urlencode($r['id_pengabdian']) ?>">Ubah</a>
                                    <a href="#" class="btn btn-danger" onclick="if(confirm('Hapus data ini?')) { document.getElementById('deleteForm<?= $r['id_pengabdian'] ?>').submit(); }">Hapus</a>
                                    
                                    <form id="deleteForm<?= $r['id_pengabdian'] ?>" action="pengabdian_delete.php" method="post" style="display:none;">
                                        <input type="hidden" name="id_pengabdian" value="<?= htmlspecialchars($r['id_pengabdian']) ?>">
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