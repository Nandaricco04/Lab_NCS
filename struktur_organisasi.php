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

// Fetch struktur organisasi data
try {
    $sql = "SELECT * FROM v_struktur_organisasi";
    $result = q($sql);
    $strukturData = pg_fetch_all($result);
} catch (Exception $e) {
    $strukturData = [];
    error_log($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struktur Organisasi - Lab NCS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/variables.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/global.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/navbar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/struktur_organisasi.css?v=<?= time() ?>">
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

        <!-- Struktur Organisasi Section -->
        <section class="section struktur-section">
            <div class="container">
                <h2 class="section-title">Struktur Organisasi</h2>

                <?php if (!empty($strukturData)): ?>
                    <div class="struktur-grid">
                        <?php foreach ($strukturData as $person): ?>
                            <div class="struktur-card">
                                <div class="struktur-photo">
                                    <?php if (!empty($person['foto_path'])): ?>
                                        <img src="admin/<?= htmlspecialchars($person['foto_path']) ?>"
                                             alt="<?= htmlspecialchars($person['nama']) ?>">
                                    <?php else: ?>
                                        <div class="photo-placeholder"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="struktur-info">
                                    <h3><?= htmlspecialchars($person['nama']) ?></h3>
                                    <p class="position"><?= htmlspecialchars($person['posisi']) ?></p>
                                    <p class="nip">Nip: <?= htmlspecialchars($person['nip']) ?></p>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <p class="text-muted">Belum ada data struktur organisasi.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
