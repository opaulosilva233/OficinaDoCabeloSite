document.addEventListener("DOMContentLoaded", function () {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const layout = document.querySelector('.dashboard-layout');
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = themeToggle ? themeToggle.querySelector('i') : null;

    // Sidebar Toggle
    if (sidebarToggle && layout) {
        sidebarToggle.addEventListener('click', function () {
            layout.classList.toggle('collapsed');

            if (window.innerWidth <= 768) {
                layout.classList.toggle('mobile-active');
            }
        });
    }

    // Theme Toggle (Switch Style)
    const themeSwitch = document.getElementById('darkModeSwitch');

    function setTheme(isLight) {
        if (isLight) {
            document.body.classList.add('light-mode');
            if (themeSwitch) themeSwitch.checked = false; // Uncheck for Light
        } else {
            document.body.classList.remove('light-mode');
            if (themeSwitch) themeSwitch.checked = true; // Check for Dark
        }
        localStorage.setItem('theme', isLight ? 'light' : 'dark');
    }

    // Initialize Theme
    const savedTheme = localStorage.getItem('theme');
    // Default is dark (no class), so if saved is 'light', apply it.
    if (savedTheme === 'light') {
        setTheme(true);
    } else {
        setTheme(false); // Ensure switch is sync
    }

    if (themeSwitch) {
        themeSwitch.addEventListener('change', function () {
            // If checked, we want Dark Mode (so isLight = false)
            // If unchecked, we want Light Mode (so isLight = true)
            setTheme(!this.checked);
        });
    }

    // Floating Tooltips for Collapsed Sidebar
    const menuItems = document.querySelectorAll('.sidebar-menu .menu-item');
    let activeTooltip = null;

    menuItems.forEach(item => {
        item.addEventListener('mouseenter', function () {
            // Only show if collapsed and not on mobile (sidebar visible)
            if (layout.classList.contains('collapsed') && window.innerWidth > 768) {
                const title = this.getAttribute('title');
                if (!title) return;

                // Create tooltip
                activeTooltip = document.createElement('div');
                activeTooltip.className = 'dashboard-tooltip';
                activeTooltip.textContent = title;
                document.body.appendChild(activeTooltip);

                // Position it
                const rect = this.getBoundingClientRect();
                activeTooltip.style.top = rect.top + (rect.height / 2) - (activeTooltip.offsetHeight / 2) + 'px';
                activeTooltip.style.left = rect.right + 15 + 'px'; // 15px gap
                activeTooltip.style.opacity = '1';

                // Add arrow
                // (CSS usually handles the arrow better, but simplistic here)
            }
        });

        item.addEventListener('mouseleave', function () {
            if (activeTooltip) {
                activeTooltip.remove();
                activeTooltip = null;
            }
        });
    });
});
