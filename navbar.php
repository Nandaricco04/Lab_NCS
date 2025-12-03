<nav class="navbar">
    <div class="container">
        <div class="nav-brand">
            <img src="images/logo.png" alt="Lab NCS Logo" class="nav-logo">
        </div>

        <ul class="nav-menu">
            <li><a href="index.php">Home</a></li>
            <li class="dropdown">
                <a href="#">Tentang Kami 
                    <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.707092 0.707108L5.70709 5.70711L10.7071 0.707108" stroke="white" stroke-width="2"/>
                    </svg>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="visi_misi.php">Visi Misi</a></li>
                    <li><a href="#">Logo</a></li>
                    <li><a href="struktur_organisasi.php">Struktur Organisasi</a></li>
                </ul>
            </li>
            <li><a href="peta_jalan.php">Peta Jalan</a></li>
            <li><a href="sarana.php">Layanan</a></li>
            <li><a href="galeri.php">Galeri</a></li>
            <li><a href="arsip.php">Arsip</a></li>
        </ul>

        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</nav>

<script>
// Navbar scroll effect
const navbar = document.querySelector('.navbar');
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Mobile menu toggle
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
});

// Close menu when clicking a link
document.querySelectorAll('.nav-menu a').forEach(link => {
    link.addEventListener('click', () => {
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
    });
});

// Dropdown functionality
document.querySelectorAll('.dropdown').forEach(dropdown => {
    dropdown.addEventListener('mouseenter', () => {
        dropdown.querySelector('.dropdown-menu').style.display = 'block';
    });
    dropdown.addEventListener('mouseleave', () => {
        dropdown.querySelector('.dropdown-menu').style.display = 'none';
    });
});
</script>
