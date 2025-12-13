document.addEventListener("DOMContentLoaded", () => {
    const dropdownToggle = document.querySelectorAll(".dropdown-toggle");
    const menuLinks = Array.from(document.querySelectorAll(".menu-link"));
    const sidebar = document.querySelector(".sidebar");

    // Clear active state
    function clearActive() {
        document.querySelectorAll(".menu li.active").forEach(li => li.classList.remove("active"));
    }

    // Check if mobile
    function isMobile() {
        return window.innerWidth <= 768;
    }

    // Expand sidebar on hover (mobile only)
    if (sidebar) {
        sidebar.addEventListener("mouseenter", () => {
            if (isMobile()) {
                sidebar.classList.add("expanded");
            }
        });

        sidebar.addEventListener("mouseleave", () => {
            if (isMobile()) {
                // Delay collapse to allow clicking
                setTimeout(() => {
                    sidebar.classList.remove("expanded");
                }, 200);
            }
        });

        // Touch support for mobile
        let touchTimeout;
        sidebar.addEventListener("touchstart", () => {
            if (isMobile()) {
                sidebar.classList.add("expanded");
                clearTimeout(touchTimeout);
            }
        });

        document.addEventListener("touchstart", (e) => {
            if (isMobile() && !sidebar.contains(e.target)) {
                touchTimeout = setTimeout(() => {
                    sidebar.classList.remove("expanded");
                }, 300);
            }
        });
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

            // Collapse sidebar on mobile after link click
            if (isMobile()) {
                setTimeout(() => {
                    sidebar.classList.remove("expanded");
                }, 100);
            }
        });
    });

    // Dropdown toggle
    dropdownToggle.forEach(toggle => {
        toggle.addEventListener("click", (e) => {
            e.stopPropagation();
            const li = toggle.closest(".dropdown");
            if (!li) return;
            
            // Close other dropdowns
            document.querySelectorAll(".dropdown.open").forEach(d => {
                if (d !== li) {
                    d.classList.remove("open");
                    d.querySelector(".dropdown-toggle")?.setAttribute("aria-expanded", "false");
                }
            });
            
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
        }
    });

    // Handle window resize
    window.addEventListener("resize", () => {
        if (!isMobile()) {
            sidebar.classList.remove("expanded");
        }
    });
});