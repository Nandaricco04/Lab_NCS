<?php
require_once 'admin/koneksi.php';

// Fetch home data for background image
try {
    $sql = "SELECT * FROM v_title_pages WHERE id_title = 2 LIMIT 1";
    $result = q($sql);
    $homeData = pg_fetch_assoc($result);
} catch (Exception $e) {
    $homeData = null;
    error_log($e->getMessage());
}

// Fetch visi data
try {
    $sql = "SELECT * FROM v_visi LIMIT 1";
    $result = q($sql);
    $visiData = pg_fetch_assoc($result);
} catch (Exception $e) {
    $visiData = null;
    error_log($e->getMessage());
}

// Fetch misi data
try {
    $sql = "SELECT * FROM v_misi ORDER BY id_misi";
    $result = q($sql);
    $misiData = pg_fetch_all($result);
} catch (Exception $e) {
    $misiData = [];
    error_log($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visi & Misi - Lab NCS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/variables.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/global.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/navbar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/visi_misi.css?v=<?= time() ?>">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <!-- Hero Section -->
        <section class="hero-section" style="background-image: url('admin/<?= htmlspecialchars($homeData['gambar_path'] ?? 'images/hero-bg.jpg') ?>');">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1><?= $homeData['judul'] ?? 'N/A' ?></h1>
            </div>
        </section>

        <!-- Visi Section -->
        <?php if (!empty($visiData)): ?>
        <section class="section visi-section">
            <div class="container">
                <h2 class="section-title">Visi</h2>
                <div class="visi-content">
                    <p><?= htmlspecialchars($visiData['isi_visi']) ?></p>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Misi Section -->
        <?php if (!empty($misiData)): ?>
        <section class="section misi-section">
            <div class="container">
                <h2 class="section-title">Misi</h2>
                <div class="misi-content">
                    <ul>
                        <?php foreach ($misiData as $misi): ?>
                            <li><?= htmlspecialchars($misi['isi_misi']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
