document.addEventListener("DOMContentLoaded", function () {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const layout = document.querySelector('.dashboard-layout');

    if (sidebarToggle && layout) {
        sidebarToggle.addEventListener('click', function () {
            layout.classList.toggle('collapsed');

            if (window.innerWidth <= 768) {
                layout.classList.toggle('mobile-active');
            }
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
