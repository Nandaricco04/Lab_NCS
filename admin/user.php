<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$res = q('SELECT "id_pengguna", "username", "password_hash", "nama_lengkap", "dibuat_pada" FROM "v_users" ORDER BY "username" ASC');
$rows = pg_fetch_all($res) ?: [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Admin</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="layout">
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="main-table-title">Daftar Admin</div>
            <p>
                <a class="btn btn-primary" href="user_create.php" style="margin-left: 0;">+ Tambah Admin</a>
            </p>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Nama Lengkap</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$rows): ?>
                        <tr>
                            <td colspan="6">Belum ada user.</td>
                        </tr>
                        <?php else: foreach ($rows as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r['id_pengguna'] ?? '') ?></td>
                                <td><?= htmlspecialchars($r['username'] ?? '') ?></td>
                                <td><?= htmlspecialchars($r['password_hash'] ?? '') ?></td>
                                <td><?= htmlspecialchars($r['nama_lengkap'] ?? '') ?></td>
                                <td><?= htmlspecialchars($r['dibuat_pada'] ?? '') ?></td>
                                <td>
                                    <a class="btn btn-warning" href="user_update.php?id=<?= urlencode($r['id_pengguna']) ?>">Ubah</a>
                                    <a href="#" class="btn btn-danger" onclick="if(confirm('Hapus data ini?')) { document.getElementById('deleteForm<?= $r['id_pengguna'] ?>').submit(); }">Hapus</a>

                                    <form id="deleteForm<?= $r['id_pengguna'] ?>" action="user_delete.php" method="post" style="display:none;">
                                        <input type="hidden" name="id_pengguna" value="<?= htmlspecialchars($r['id_pengguna']) ?>">
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