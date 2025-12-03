<?php
require_once 'admin/koneksi.php';

// Fetch home data for background image
try {
    $sql = "SELECT * FROM v_title_pages WHERE id_title = 3 LIMIT 1";
    $result = q($sql);
    $homeData = pg_fetch_assoc($result);
} catch (Exception $e) {
    $homeData = null;
    error_log($e->getMessage());
}

// Mockup data for Peta Jalan
$petaJalanData = [
    [
        'tahun' => '2021 - 2022',
        'deskripsi' => 'Pengembangan infrastruktur laboratorium dengan penambahan perangkat keamanan siber modern dan peningkatan kapasitas jaringan.',
        'items' => [
            'Peningkatan perangkat keras jaringan dan keamanan',
            'Implementasi sistem monitoring real-time',
            'Pelatihan sumber daya manusia di bidang cyber security'
        ]
    ],
    [
        'tahun' => '2022 - 2023',
        'deskripsi' => 'Ekspansi penelitian dan kolaborasi dengan industri dalam pengembangan solusi keamanan siber untuk UMKM.',
        'items' => [
            'Kerjasama dengan perusahaan teknologi keamanan',
            'Publikasi penelitian di jurnal internasional',
            'Workshop dan seminar keamanan siber'
        ]
    ],
    [
        'tahun' => '2023 - 2024',
        'deskripsi' => 'Pengembangan pusat riset keamanan siber dan implementasi teknologi IoT security untuk smart city.',
        'items' => [
            'Membangun testbed IoT security',
            'Riset AI untuk deteksi ancaman siber',
            'Sertifikasi internasional untuk laboratorium'
        ]
    ],
    [
        'tahun' => '2024 - 2025',
        'deskripsi' => 'Menjadi pusat unggulan keamanan siber di Indonesia dengan fokus pada riset quantum cryptography dan blockchain security.',
        'items' => [
            'Penelitian quantum-resistant cryptography',
            'Implementasi blockchain untuk keamanan data',
            'Kerjasama internasional dengan universitas terkemuka'
        ]
    ],
    [
        'tahun' => '2025 - 2026',
        'deskripsi' => 'Pengembangan ekosistem keamanan siber nasional dengan pusat pelatihan dan sertifikasi profesional.',
        'items' => [
            'Pusat sertifikasi profesional keamanan siber',
            'Platform e-learning keamanan siber nasional',
            'Kompetisi CTF (Capture The Flag) tingkat nasional'
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Jalan - Lab NCS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/variables.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/global.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/navbar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/peta_jalan.css?v=<?= time() ?>">
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

        <!-- Peta Jalan Section -->
        <section class="section peta-jalan-section">
            <div class="container">
                <h2 class="section-title">Peta Jalan 2021-2026</h2>

                <div class="timeline">
                    <?php foreach ($petaJalanData as $index => $item): ?>
                        <div class="timeline-item <?= $index % 2 === 0 ? 'timeline-left' : 'timeline-right' ?>">
                            <div class="timeline-card">
                                <h3 class="timeline-year"><?= htmlspecialchars($item['tahun']) ?></h3>
                                <p class="timeline-description"><?= htmlspecialchars($item['deskripsi']) ?></p>
                                <ol class="timeline-list">
                                    <?php foreach ($item['items'] as $listItem): ?>
                                        <li><?= htmlspecialchars($listItem) ?></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
