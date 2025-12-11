<?php
// session_start();

if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <script src="https://code.iconify.design/3/3.1.1/iconify.min.js" defer></script>
    <link rel="stylesheet" href="style.css" />
    <script defer src="sidebar.js"></script>
</head>

<body>
    <?php
    $activePage = basename($_SERVER['PHP_SELF']);
    ?>
    
    <!-- Hamburger Menu Button -->
    <button class="hamburger" aria-label="Toggle menu">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">Lab NCS</div>
        <ul class="menu">
            <li class="<?= $activePage == 'index.php' ? 'active' : '' ?>">
                <a href="index.php" class="menu-link">
                    <span class="iconify" data-icon="mdi:view-dashboard" data-width="20" data-height="20"></span>
                    <span class="label">Dashboard</span>
                </a>
            </li>
            <li class="<?= $activePage == 'user.php' ? 'active' : '' ?>">
                <a href="user.php" class="menu-link">
                    <span class="iconify" data-icon="mdi:account-tie" data-width="20" data-height="20"></span>
                    <span class="label">Admin</span>
                </a>
            </li>
            <li class="<?= $activePage == 'home.php' ? 'active' : '' ?>">
                <a href="home.php" class="menu-link">
                    <span class="iconify" data-icon="mdi:home" data-width="20" data-height="20"></span>
                    <span class="label">Home</span>
                </a>
            </li>
            <li class="<?= $activePage == 'title.php' ? 'active' : '' ?>">
                <a href="title.php" class="menu-link">
                    <span class="iconify" data-icon="mdi:file-document" data-width="20" data-height="20"></span>
                    <span class="label">Title Page</span>
                </a>
            </li>
            <li class="dropdown<?= in_array($activePage, ['sejarah.php', 'visi.php', 'misi.php', 'pengelola_lab.php']) ? ' open' : '' ?>">
                <div class="dropdown-toggle menu-link" role="button" tabindex="0" aria-expanded="<?= in_array($activePage, ['sejarah.php', 'visi.php', 'misi.php', 'pengelola_lab.php']) ? 'true' : 'false' ?>">
                    <span class="iconify" data-icon="mdi:information" data-width="20" data-height="20"></span>
                    <span class="label">Tentang Kami</span>
                    <span class="caret" aria-hidden="true">▾</span>
                </div>
                <ul class="dropdown-menu">
                    <li>
                        <a href="visi.php" class="menu-link<?= $activePage == 'visi.php' ? ' active' : '' ?>">
                            <span class="iconify" data-icon="mdi:target" data-width="20" data-height="20"></span>
                            <span class="label">Visi</span>
                        </a>
                    </li>
                    <li>
                        <a href="misi.php" class="menu-link<?= $activePage == 'misi.php' ? ' active' : '' ?>">
                            <span class="iconify" data-icon="mdi:target" data-width="20" data-height="20"></span>
                            <span class="label">Misi</span>
                        </a>
                    </li>
                    <li>
                        <a href="struktur_organisasi.php" class="menu-link<?= $activePage == 'struktur_organisasi.php' ? ' active' : '' ?>">
                            <span class="iconify" data-icon="mdi:account-group" data-width="20" data-height="20"></span>
                            <span class="label">Struktur Organisasi</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="<?= $activePage == 'peta_jalan.php' ? 'active' : '' ?>">
                <a href="peta_jalan.php" class="menu-link">
                    <span class="iconify" data-icon="mdi:map" data-width="20" data-height="20"></span>
                    <span class="label">Peta Jalan</span>
                </a>
            </li>
            <li class="<?= $activePage == 'galeri.php' ? 'active' : '' ?>">
                <a href="galeri.php" class="menu-link">
                    <span class="iconify" data-icon="mdi:image-multiple" data-width="20" data-height="20"></span>
                    <span class="label">Galeri</span>
                </a>
            </li>
            <li class="<?= $activePage == 'foto_lab.php' ? 'active' : '' ?>">
                <a href="foto_lab.php" class="menu-link">
                    <span class="iconify" data-icon="mdi:camera" data-width="20" data-height="20"></span>
                    <span class="label">Foto Lab</span>
                </a>
            </li>
            <li class="dropdown<?= in_array($activePage, ['sarana.php', 'alat.php', 'peminjaman.php', 'detail.php']) ? ' open' : '' ?>">
                <div class="dropdown-toggle menu-link" role="button" tabindex="0" aria-expanded="<?= in_array($activePage, ['sarana.php', 'alat.php', 'peminjaman.php', 'detail.php']) ? 'true' : 'false' ?>">
                    <span class="iconify" data-icon="mdi:briefcase" data-width="20" data-height="20"></span>
                    <span class="label">Layanan</span>
                    <span class="caret" aria-hidden="true">▾</span>
                </div>
                <ul class="dropdown-menu">
                    <li>
                        <a href="sarana.php" class="menu-link<?= $activePage == 'sarana.php' ? ' active' : '' ?>">
                            <span class="iconify" data-icon="mdi:domain" data-width="20" data-height="20"></span>
                            <span class="label">Sarana dan Prasarana</span>
                        </a>
                    </li>
                    <li>
                        <a href="peminjaman.php" class="menu-link<?= $activePage == 'peminjaman.php' ? ' active' : '' ?>">
                            <span class="iconify" data-icon="mdi:calendar-clock" data-width="20" data-height="20"></span>
                            <span class="label">Peminjaman</span>
                        </a>
                    </li>
                    <li>
                        <a href="konsultasi.php" class="menu-link<?= $activePage == 'konsultasi.php' ? ' active' : '' ?>">
                            <span class="iconify" data-icon="mdi:account-question" data-width="20" data-height="20"></span>
                            <span class="label">Konsultasi</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dropdown<?= in_array($activePage, ['penelitian.php', 'pengabdian.php', 'detail_pengabdian.php']) ? ' open' : '' ?>">
                <div class="dropdown-toggle menu-link" role="button" tabindex="0" aria-expanded="<?= in_array($activePage, ['penelitian.php', 'pengabdian.php', 'detail_pengabdian.php']) ? 'true' : 'false' ?>">
                    <span class="iconify" data-icon="mdi:archive" data-width="20" data-height="20"></span>
                    <span class="label">Arsip</span>
                    <span class="caret" aria-hidden="true">▾</span>
                </div>
                <ul class="dropdown-menu">
                    <li class="<?= $activePage == 'penelitian.php' ? 'active' : '' ?>">
                        <a href="penelitian.php" class="menu-link<?= $activePage == 'penelitian.php' ? ' active' : '' ?>">
                            <span class="iconify" data-icon="mdi:microscope" data-width="20" data-height="20"></span>
                            <span class="label">Penelitian</span>
                        </a>
                    </li>
                    <li class="<?= $activePage == 'pengabdian.php' ? 'active' : '' ?>">
                        <a href="pengabdian.php" class="menu-link<?= $activePage == 'pengabdian.php' ? ' active' : '' ?>">
                            <span class="iconify" data-icon="mdi:hand-heart" data-width="20" data-height="20"></span>
                            <span class="label">Pengabdian</span>
                        </a>
                    </li>
                    <li class="<?= $activePage == 'detail_pengabdian.php' ? 'active' : '' ?>">
                        <a href="detail_pengabdian.php" class="menu-link<?= $activePage == 'detail_pengabdian.php' ? ' active' : '' ?>">
                            <span class="iconify" data-icon="mdi:file-document-multiple" data-width="20" data-height="20"></span>
                            <span class="label">Detail Pengabdian</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <a class="logout" href="logout.php">
            <span class="iconify" data-icon="mdi:logout" data-width="20" data-height="20"></span> Logout
        </a>
    </div>
</body>

</html>