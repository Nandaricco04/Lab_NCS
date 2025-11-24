document.addEventListener("DOMContentLoaded", () => {
    const dropdownToggle = document.querySelectorAll(".dropdown-toggle");
    const menuLinks = Array.from(document.querySelectorAll(".menu-link"));

    function clearActive() {
        document.querySelectorAll(".menu li.active").forEach(li => li.classList.remove("active"));
    }

    // clicking non-dropdown links sets them active
    menuLinks.forEach(link => {
        link.addEventListener("click", (e) => {
            // if it's a dropdown header, let dropdown handler manage open/close
            if (link.classList.contains("dropdown-toggle")) return;

            const li = link.closest("li");
            if (!li) return;

            clearActive();
            li.classList.add("active");

            // open parent dropdown if any
            const parentDropdown = li.closest(".dropdown");
            if (parentDropdown) {
                parentDropdown.classList.add("open");
                parentDropdown.querySelector(".dropdown-toggle")?.setAttribute("aria-expanded", "true");
            } else {
                // close other dropdowns
                document.querySelectorAll(".dropdown.open").forEach(d => {
                    d.classList.remove("open");
                    d.querySelector(".dropdown-toggle")?.setAttribute("aria-expanded", "false");
                });
            }
        });
    });

    // dropdown toggles
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

    // close dropdowns when clicking outside
    document.addEventListener("click", (e) => {
        if (!e.target.closest(".sidebar")) {
            document.querySelectorAll(".dropdown.open").forEach(d => {
                d.classList.remove("open");
                d.querySelector(".dropdown-toggle")?.setAttribute("aria-expanded", "false");
            });
        }
    });
});