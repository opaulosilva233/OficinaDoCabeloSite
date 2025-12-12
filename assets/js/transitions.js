document.addEventListener('DOMContentLoaded', () => {
    // Add loaded class to body to trigger entry animation
    setTimeout(() => {
        document.body.classList.add('page-loaded');
    }, 50);

    const links = document.querySelectorAll('a');

    links.forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            const target = link.getAttribute('target');

            // Skip if no href, external link, anchor, or open in new tab
            if (!href ||
                href.startsWith('#') ||
                href.startsWith('javascript:') ||
                href.startsWith('mailto:') ||
                href.startsWith('tel:') ||
                target === '_blank' ||
                e.ctrlKey ||
                e.metaKey) {
                return;
            }

            // Check if it's the same domain
            if (link.hostname !== window.location.hostname) {
                return;
            }

            e.preventDefault();

            // Start Exit Animation
            document.body.classList.remove('page-loaded');
            document.body.classList.add('is-transitioning');

            // Wait for animation to finish before navigating
            // Total time = longest transition duration + max delay
            // CSS: 0.5s duration + 0.2s delay = ~700ms
            setTimeout(() => {
                window.location.href = href;
            }, 800);
        });
    });

    // Handle Page Show (Restore from bfcache)
    window.addEventListener('pageshow', (event) => {
        // Always reset on show
        document.body.classList.remove('is-transitioning');
        document.body.classList.add('page-loaded');
    });

    // Handle Page Hide (Save to bfcache)
    window.addEventListener('pagehide', (event) => {
        // Remove the transitioning class so the cached page is clean
        document.body.classList.remove('is-transitioning');
        document.body.classList.add('page-loaded');
    });
});
