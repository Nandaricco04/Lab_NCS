document.addEventListener("DOMContentLoaded", () => {
    const dropdownToggle = document.querySelectorAll(".dropdown-toggle");
    const menuLinks = Array.from(document.querySelectorAll(".menu-link"));
    const sidebar = document.querySelector(".sidebar");
    const hamburger = document.querySelector(".hamburger");
    const overlay = document.querySelector(".sidebar-overlay");

    // Clear active state
    function clearActive() {
        document.querySelectorAll(".menu li.active").forEach(li => li.classList.remove("active"));
    }

    // Close sidebar on mobile
    function closeSidebar() {
        if (window.innerWidth <= 768) {
            sidebar.classList.remove("active");
            hamburger.classList.remove("active");
            overlay.classList.remove("active");
        }
    }

    // Toggle sidebar on mobile
    if (hamburger) {
        hamburger.addEventListener("click", () => {
            sidebar.classList.toggle("active");
            hamburger.classList.toggle("active");
            overlay.classList.toggle("active");
        });
    }

    // Close sidebar when overlay is clicked
    if (overlay) {
        overlay.addEventListener("click", closeSidebar);
    }

    // Menu link clicks
    menuLinks.forEach(link => {
        link.addEventListener("click", (e) => {
            if (link.classList.contains("dropdown-toggle")) return;

            const li = link.closest("li");
            if (!li) return;

            clearActive();
            li.classList.add("active");

            const parentDropdown = li.closest(".dropdown");
            if (parentDropdown) {
                parentDropdown.classList.add("open");
                parentDropdown.querySelector(".dropdown-toggle")?.setAttribute("aria-expanded", "true");
            } else {
                document.querySelectorAll(".dropdown.open").forEach(d => {
                    d.classList.remove("open");
                    d.querySelector(".dropdown-toggle")?.setAttribute("aria-expanded", "false");
                });
            }

            // Close sidebar on mobile after clicking a link
            closeSidebar();
        });
    });

    // Dropdown toggle
    dropdownToggle.forEach(toggle => {
        toggle.addEventListener("click", () => {
            const li = toggle.closest(".dropdown");
            if (!li) return;
            const isOpen = li.classList.toggle("open");
            toggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
        });

        toggle.addEventListener("keydown", (e) => {
            if (e.key === "Enter" || e.key === " ") {
                e.preventDefault();
                toggle.click();
            }
        });
    });

    // Close dropdowns when clicking outside sidebar
    document.addEventListener("click", (e) => {
        if (!e.target.closest(".sidebar")) {
            document.querySelectorAll(".dropdown.open").forEach(d => {
                d.classList.remove("open");
                d.querySelector(".dropdown-toggle")?.setAttribute("aria-expanded", "false");
            });
            
            // Close sidebar on mobile when clicking outside
            if (window.innerWidth <= 768 && !e.target.closest(".hamburger")) {
                closeSidebar();
            }
        }
    });

    // Handle window resize
    window.addEventListener("resize", () => {
        if (window.innerWidth > 768) {
            sidebar.classList.remove("active");
            hamburger.classList.remove("active");
            overlay.classList.remove("active");
        }
    });
});