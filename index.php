<?php
require_once 'admin/koneksi.php';

try {
    $sql = "SELECT * FROM v_pages LIMIT 1";
    $result = q($sql);
    $homeData = pg_fetch_assoc($result);
} catch (Exception $e) {
    $homeData = null;
    error_log($e->getMessage());
}

try {
    $sql = "SELECT * FROM agenda_kegiatan ORDER BY id_galeri DESC LIMIT 6";
    $result = q($sql);
    $galleryItems = pg_fetch_all($result);
} catch (Exception $e) {
    $galleryItems = [];
    error_log($e->getMessage());
}

try {
    $limit = 3;
    $sql = "SELECT * FROM penelitian ORDER BY id_penelitian DESC LIMIT $1";
    $result = qparams($sql, [$limit]);
    $researchItems = pg_fetch_all($result);
} catch (Exception $e) {
    $researchItems = [];
    error_log($e->getMessage());
}

// Fetch lab room images
try {
    $sql = "SELECT * FROM v_ruang_lab";
    $result = q($sql);
    $labRoomImages = pg_fetch_all($result);
} catch (Exception $e) {
    $labRoomImages = [];
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

// Fetch struktur organisasi data
try {
    $sql = "SELECT * FROM v_struktur_organisasi LIMIT 3";
    $result = q($sql);
    $strukturData = pg_fetch_all($result);
} catch (Exception $e) {
    $strukturData = [];
    error_log($e->getMessage());
}

// Fetch penelitian data for Arsip section
try {
    $sql = "SELECT judul, tahun, file_path FROM v_penelitian ORDER BY tahun DESC LIMIT 4";
    $result = q($sql);
    $arsipData = pg_fetch_all($result);
} catch (Exception $e) {
    $arsipData = [];
    error_log($e->getMessage());
}

// Fetch agenda kegiatan for Galeri grid section
try {
    $sql = "SELECT foto FROM v_agenda_kegiatan ORDER BY tanggal_mulai DESC LIMIT 4";
    $result = q($sql);
    $galeriGridItems = pg_fetch_all($result);
} catch (Exception $e) {
    $galeriGridItems = [];
    error_log($e->getMessage());
}

// Fetch sarana prasarana for Fasilitas & Layanan section
try {
    $sql = "SELECT gambar_path, judul, sub_judul, jumlah_alat FROM v_sarana_prasarana ORDER BY id_sarana_prasarana DESC LIMIT 2";
    $result = q($sql);
    $fasilitasItems = pg_fetch_all($result);
} catch (Exception $e) {
    $fasilitasItems = [];
    error_log($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab NCS - Laboratorium Network and Computer System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <!-- Hero Section with Background Image -->
        <section class="hero-fullscreen" style="background-image: url('admin/<?= htmlspecialchars($homeData['gambar_path'] ?? 'default-hero.jpg') ?>'); background-size: contain; background-position: center; background-repeat: no-repeat; background-size: calc(100% + 100px);">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1><?= $homeData['judul'] ?? 'N/A' ?></h1>
            </div>
        </section>

        <!-- Profil Laboratorium Section -->
        <section class="section profil-section">
            <div class="container">
                <h2 class="text-center mb-4">PROFIL LABORATORIUM</h2>
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <p>
                            Unit penunjang akademik di Jurusan Teknologi Informasi yang difokuskan pada pengembangan
                            kompetensi di bidang jaringan komputer dan keamanan siber. Laboratorium ini mendukung
                            kegiatan praktikum, penelitian, dan pengembangan yang berkaitan dengan administrasi jaringan,
                            pengamanan infrastruktur, pemantauan lalu lintas data, hingga penerapan teknologi keamanan
                            informasi dalam melindungi aset digital organisasi.
                        </p>
                        <p style="margin-top: 20px;">
                            Selain menjadi sarana pembelajaran mahasiswa, Laboratorium Jaringan & Keamanan Siber juga
                            berperan sebagai wadah penelitian dan inovasi bagi dosen maupun mahasiswa dalam mengkaji
                            solusi di bidang keamanan jaringan, pengujian sistem, serta perlindungan data dan infrastruktur
                            digital. Peran laboratorium ini diharapkan mampu memperkuat kualitas pembelajaran,
                            memperdalam riset di bidang jaringan komputer dan keamanan informasi, serta mendukung
                            terciptanya lulusan yang kompeten di bidang administrasi jaringan dan keamanan siber.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ruangan Laboratorium Carousel Section -->
        <?php if (!empty($labRoomImages)): ?>
        <section class="section lab-carousel-section">
            <div class="container">
                <h2 class="text-center mb-5">Ruangan Laboratorium</h2>

                <div id="labCarousel" class="carousel slide" data-bs-ride="carousel">
                    <?php
                    // Split images into chunks of 3 for each slide
                    $imageChunks = array_chunk($labRoomImages, 3);
                    $totalSlides = count($imageChunks);
                    ?>

                    <!-- Indicators -->
                    <div class="carousel-indicators">
                        <?php for ($i = 0; $i < $totalSlides; $i++): ?>
                            <button type="button" data-bs-target="#labCarousel" data-bs-slide-to="<?= $i ?>" <?= $i === 0 ? 'class="active"' : '' ?>></button>
                        <?php endfor; ?>
                    </div>

                    <!-- Slides -->
                    <div class="carousel-inner">
                        <?php foreach ($imageChunks as $index => $chunk): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <div class="row g-3 px-5">
                                    <?php foreach ($chunk as $room): ?>
                                        <div class="col-md-4">
                                            <img src="admin/<?= htmlspecialchars($room['gambar_path']) ?>"
                                                 class="d-block w-100"
                                                 alt="Lab Room">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#labCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#labCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Visi & Misi Section -->
        <section class="section visi-misi-section">
            <div class="container">
                <!-- VISI -->
                <?php if (!empty($visiData)): ?>
                <div class="mb-4 visi-misi-box">
                    <h2 class="mb-4">VISI</h2>
                    <p style="margin-bottom: 0;">
                        <?= htmlspecialchars($visiData['isi_visi']) ?>
                    </p>
                </div>
                <?php endif; ?>

                <!-- MISI -->
                <?php if (!empty($misiData)): ?>
                <div class="visi-misi-box">
                    <h2 class="mb-4">MISI</h2>
                    <ul style="margin-bottom: 0;">
                        <?php foreach ($misiData as $index => $misi): ?>
                            <li class="<?= $index === count($misiData) - 1 ? 'mb-0' : 'mb-3' ?>">
                                <?= htmlspecialchars($misi['isi_misi']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Pengelola Laboratorium Section -->
        <?php if (!empty($strukturData)): ?>
        <section class="section pengelola-section">
            <div class="container">
                <h2 class="text-center mb-5">Pengelola Laboratorium</h2>

                <div class="row g-4 justify-content-center mb-5">
                    <?php foreach ($strukturData as $person): ?>
                        <div class="col-md-4">
                            <div class="text-center profile-card">
                                <?php if (!empty($person['foto_path'])): ?>
                                    <img src="admin/<?= htmlspecialchars($person['foto_path']) ?>"
                                         alt="<?= htmlspecialchars($person['nama']) ?>"
                                         class="mb-4 mx-auto profile-img">
                                <?php else: ?>
                                    <div class="mb-4 mx-auto profile-img" style="background: var(--color-primary);"></div>
                                <?php endif; ?>
                                <h5>
                                    <?= htmlspecialchars($person['nama']) ?>
                                </h5>
                                <p>
                                    <?= htmlspecialchars($person['posisi']) ?>
                                </p>
                                <p>
                                    Nip: <?= htmlspecialchars($person['nip']) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="text-center">
                    <a href="struktur_organisasi.php" class="btn btn-light btn-lg btn-selengkapnya">Selengkapnya</a>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Arsip Section -->
        <section class="section arsip-section bg-light">
            <div class="container">
                <h2 class="text-center mb-5">Arsip Penelitian</h2>
                <?php if (!empty($arsipData)): ?>
                <div class="row g-4 mb-4">
                    <?php foreach ($arsipData as $arsip): ?>
                        <div class="col-md-3 col-sm-6">
                            <a href="admin/<?= htmlspecialchars($arsip['file_path']) ?>" target="_blank" class="text-decoration-none">
                                <div class="arsip-card">
                                    <div class="arsip-pdf-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <path d="M10 12h4"></path>
                                            <path d="M10 16h4"></path>
                                        </svg>
                                        <span class="pdf-badge">PDF</span>
                                    </div>
                                    <div class="arsip-content">
                                        <h5 class="arsip-title"><?= htmlspecialchars($arsip['judul']) ?></h5>
                                        <p class="arsip-year">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                            <?= htmlspecialchars($arsip['tahun']) ?>
                                        </p>
                                    </div>
                                    <div class="arsip-download">
                                        <span>Lihat Dokumen</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada data arsip penelitian.</p>
                </div>
                <?php endif; ?>
                <div class="text-end">
                    <a href="arsip.php" class="btn btn-primary btn-lg px-5 py-3">Selengkapnya</a>
                </div>
            </div>
        </section>

        <!-- Galeri Section -->
        <section class="section galeri-section">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-5 galeri-text-side">
                        <h1>Galeri</h1>
                        <p>Kumpulan foto Labolatorium Jaringan dan Keamanan Cyber</p>
                        <a href="galeri.php" class="btn btn-light btn-lg px-5 py-3">Selengkapnya</a>
                    </div>
                    <div class="col-lg-7 galeri-grid-side">
                        <div class="galeri-grid">
                            <?php if (!empty($galeriGridItems)): ?>
                                <?php foreach ($galeriGridItems as $index => $item): ?>
                                    <div class="galeri-item">
                                        <img src="admin/<?= htmlspecialchars($item['foto']) ?>"
                                             alt="Galeri Kegiatan"
                                             class="galeri-grid-image">
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="galeri-item">
                                    <div class="galeri-placeholder"></div>
                                </div>
                                <div class="galeri-item">
                                    <div class="galeri-placeholder"></div>
                                </div>
                                <div class="galeri-item">
                                    <div class="galeri-placeholder"></div>
                                </div>
                                <div class="galeri-item">
                                    <div class="galeri-placeholder"></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Produk Layanan Section -->
        <section class="section produk-layanan-section bg-light">
            <div class="container">
                <h2 class="text-center mb-5">Produk Layanan</h2>

                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3 class="mb-0">Fasilitas & Peralatan</h3>
                    <a href="sarana.php" class="btn btn-primary btn-lg px-5 py-3">Selengkapnya</a>
                </div>

                <?php if (!empty($fasilitasItems)): ?>
                <div class="row g-4">
                    <?php foreach ($fasilitasItems as $fasilitas): ?>
                        <div class="col-md-6">
                            <div class="produk-card">
                                <div class="produk-image">
                                    <img src="admin/<?= htmlspecialchars($fasilitas['gambar_path']) ?>"
                                         alt="<?= htmlspecialchars($fasilitas['judul']) ?>"
                                         class="img-fluid">
                                </div>
                                <div class="produk-body">
                                    <h4><?= htmlspecialchars($fasilitas['judul']) ?></h4>
                                    <p><?= htmlspecialchars($fasilitas['sub_judul']) ?></p>
                                    <p class="text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;">
                                            <path d="M20 7h-9"></path>
                                            <path d="M14 17H5"></path>
                                            <circle cx="17" cy="17" r="3"></circle>
                                            <circle cx="7" cy="7" r="3"></circle>
                                        </svg>
                                        Tersedia <?= htmlspecialchars($fasilitas['jumlah_alat']) ?> Unit
                                    </p>
                                    <a href="sarana.php" class="btn btn-outline-primary">Ajukan Pinjaman</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada data fasilitas & peralatan.</p>
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
