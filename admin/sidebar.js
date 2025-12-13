document.addEventListener("DOMContentLoaded", () => {
    const dropdownToggle = document.querySelectorAll(".dropdown-toggle");
    const menuLinks = Array.from(document.querySelectorAll(".menu-link"));
    const sidebar = document.querySelector(".sidebar");
    const overlay = document.querySelector(".sidebar-overlay");

    // Clear active state
    function clearActive() {
        document.querySelectorAll(".menu li.active").forEach(li => li.classList.remove("active"));
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
        });
    });

    // Dropdown toggle (accordion: only one open)
    dropdownToggle.forEach(toggle => {
        toggle.addEventListener("click", () => {
            const li = toggle.closest(".dropdown");
            if (!li) return;

            // Tutup dropdown lain
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
});