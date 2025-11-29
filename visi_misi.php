<?php
$qVisi = "SELECT * FROM vw_konten_lab where judul_konten = 'Visi Lab'";
    $rVisi = pg_query($conn, $qVisi);
    $rowVisi = pg_fetch_assoc($rVisi);

    $qMisi = "SELECT * FROM vw_konten_lab where judul_konten = 'Misi Lab'";
    $rMisi = pg_query($conn, $qMisi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visi & Misi - Jurusan Teknologi Informasi</title>
    <link rel="stylesheet" href="styleVisi.css">
</head>

<body>
    <div class="visi-banner">
        <nav class="navbar">
            <div class="navbar-brand">
                <img src="images/logo.png" alt="Logo JTI Polinema">
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item" style="position:relative;">
                    <a href="#" class="nav-link">
                        Tentang Kami <span class="dropdown-icon">▼</span>
                    </a>
                    <div class="dropdown-menu">
                        <a href="visi_misi.php" class="dropdown-item">Visi & Misi</a>
                        <a href="#" class="dropdown-item">Sejarah</a>
                        <a href="#" class="dropdown-item">Struktur Organisasi</a>
                        <a href="#" class="dropdown-item">Dosen & Staff</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Peta Jalan</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Layanan</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Galeri</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Arsip</a>
                </li>
            </ul>
        </nav>
        <div class="gradient-overlay"></div>
        <div class="hero-content">
            <div class="hero-text">
                <h1>Visi & Misi</h1>
            </div>
        </div>
    </div>

    <section class="content-section">
        <div class="visi-section">
            <h2>Visi</h2>
            <p>Menjadi laboratorium unggulan dalam bidang jaringan dan keamanan siber yang inovatif, adaptif, dan berdaya saing global, serta mendukung pengembangan pendidikan, penelitian, dan pengabdian masyarakat di bidang Teknologi Informasi.</p>
        </div>
        <div class="misi-section">
            <h2>Misi</h2>
            <ul>
                <li>Menyelenggarakan kegiatan praktikum yang berkualitas untuk menghasilkan lulusan dengan kompetensi tinggi di bidang jaringan komputer dan keamanan siber.</li>
                <li>Mengembangkan penelitian terapan dalam bidang jaringan, keamanan sistem, Internet of Things (IoT), serta teknologi monitoring yang relevan dengan kebutuhan industri dan masyarakat.</li>
                <li>Menyediakan layanan uji keamanan, pendampingan, serta pelatihan bagi civitas akademika maupun mitra eksternal melalui kerja sama dengan industri, pemerintah, dan lembaga lainnya.</li>
                <li>Meningkatkan kapasitas dosen, teknisi, dan asisten laboratorium dalam menguasai teknologi terkini melalui pelatihan, sertifikasi, dan partisipasi aktif dalam komunitas profesional.</li>
                <li>Mendukung peningkatan literasi keamanan digital masyarakat melalui workshop, seminar, dan program pengabdian berbasis teknologi informasi.</li>
            </ul>
        </div>
    </section>
    
    <div>
        <?php include 'footer.php'; ?>
    </div>
    
    <script src="style.js"></script>
</body>
</html>