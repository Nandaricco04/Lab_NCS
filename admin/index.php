<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$res = q("SELECT * FROM v_dashboard");
$dash = pg_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>

    <div class="layout">
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="main-table-title">Dashboard</div>

            <div class="dashboard-cards">

            <div class="dashboard-card">
                <div class="card-title">Total User</div>
                <div class="card-value"><?= $dash['total_user'] ?></div>
            </div>

            <div class="dashboard-card">
                <div class="card-title">Total Dosen</div>
                <div class="card-value"><?= $dash['total_dosen'] ?></div>
            </div>

            <div class="dashboard-card">
                <div class="card-title">Total Pengabdian</div>
                <div class="card-value"><?= $dash['total_pengabdian'] ?></div>
            </div>

            <div class="dashboard-card">
                <div class="card-title">Total Penelitian</div>
                <div class="card-value"><?= $dash['total_penelitian'] ?></div>
            </div>

            <div class="dashboard-card">
                <div class="card-title">Total Galeri</div>
                <div class="card-value"><?= $dash['total_galeri'] ?></div>
            </div>

            <div class="dashboard-card">
                <div class="card-title">Total Peminjaman</div>
                <div class="card-value"><?= $dash['total_peminjaman'] ?></div>
            </div>
            
        </div>
    </div>
</body>

</html>