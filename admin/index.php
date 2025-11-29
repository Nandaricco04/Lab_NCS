<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';
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
                    <div class="card-value"><?= jumlahUsers(); ?></div>
                    <span class="card-icon">
                        <iconify-icon icon="mdi:account-group"></iconify-icon>
                    </span>
                </div>

                <div class="dashboard-card">
                    <div class="card-title">Total Pengabdian</div>
                    <div class="card-value"><?= jumlahPengabdian(); ?></div>
                    <span class="card-icon">
                        <iconify-icon icon="mdi:hand-heart"></iconify-icon>
                    </span>
                </div>

                <div class="dashboard-card">
                    <div class="card-title">Total Agenda Kegiatan</div>
                    <div class="card-value"><?= jumlahGaleri(); ?></div>
                    <span class="card-icon">
                        <iconify-icon icon="mdi:calendar-check"></iconify-icon>
                    </span>
                </div>

                <div class="dashboard-card">
                    <div class="card-title">Total Peminjaman</div>
                    <div class="card-value"><?= jumlahPeminjaman(); ?></div>
                    <span class="card-icon">
                        <iconify-icon icon="mdi:book-open-page-variant"></iconify-icon>
                    </span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>