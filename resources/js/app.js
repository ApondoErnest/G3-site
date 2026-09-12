document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('[data-g3-header]');
    const toggles = document.querySelectorAll('[data-mobile-nav-toggle]');
    const panel = document.querySelector('[data-mobile-nav-panel]');
    const backdrop = document.querySelector('[data-mobile-nav-backdrop]');

    if (header) {
        const onScroll = () => {
            header.dataset.scrolled = window.scrollY > 8 ? 'true' : 'false';
        };

        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    if (! toggles.length || ! panel) {
        return;
    }

    const setOpen = (open) => {
        panel.dataset.open = open ? 'true' : 'false';
        toggles.forEach((toggle) => {
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        document.body.classList.toggle('overflow-hidden', open);

        if (backdrop) {
            backdrop.dataset.open = open ? 'true' : 'false';
        }
    };

    toggles.forEach((toggle) => {
        toggle.addEventListener('click', () => {
            setOpen(panel.dataset.open !== 'true');
        });
    });

    backdrop?.addEventListener('click', () => setOpen(false));

    panel.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setOpen(false));
    });

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });
});
