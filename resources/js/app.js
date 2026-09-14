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

    document.querySelectorAll('[data-home-control]').forEach((section) => {
        const tabs = Array.from(section.querySelectorAll('[data-control-tab]'));
        const panel = section.querySelector('[data-control-panel]');
        const cardTitle = section.querySelector('[data-control-card-title]');
        const cardBody = section.querySelector('[data-control-card-body]');
        const cardIcon = section.querySelector('[data-control-card-icon]');

        if (! tabs.length || ! panel || ! cardTitle || ! cardBody || ! cardIcon) {
            return;
        }

        const activateTab = (activeTab) => {
            tabs.forEach((tab) => {
                const isActive = tab === activeTab;

                tab.classList.toggle('g3-home-control__tab--active', isActive);
                tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            cardTitle.textContent = activeTab.dataset.controlTitle ?? '';
            cardBody.textContent = activeTab.dataset.controlBody ?? '';
            cardIcon.style.setProperty('--g3-icon', `url("${activeTab.dataset.controlIcon ?? ''}")`);
            panel.setAttribute('aria-labelledby', activeTab.id);
        };

        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => activateTab(tab));

            tab.addEventListener('keydown', (event) => {
                const keyIndexMap = {
                    ArrowDown: (index + 1) % tabs.length,
                    ArrowRight: (index + 1) % tabs.length,
                    ArrowLeft: (index - 1 + tabs.length) % tabs.length,
                    ArrowUp: (index - 1 + tabs.length) % tabs.length,
                    End: tabs.length - 1,
                    Home: 0,
                };

                if (! (event.key in keyIndexMap)) {
                    return;
                }

                event.preventDefault();
                tabs[keyIndexMap[event.key]].focus();
                activateTab(tabs[keyIndexMap[event.key]]);
            });
        });
    });

    document.querySelectorAll('[data-equipment-carousel]').forEach((carousel) => {
        const viewport = carousel.querySelector('[data-equipment-viewport]');
        const page = carousel.querySelector('[data-equipment-page]');
        const cards = Array.from(carousel.querySelectorAll('[data-equipment-card]'));
        const previousButtons = carousel.querySelectorAll('[data-equipment-prev]');
        const nextButtons = carousel.querySelectorAll('[data-equipment-next]');
        const mobileQuery = window.matchMedia('(max-width: 639px)');
        const tabletQuery = window.matchMedia('(max-width: 1023px)');

        if (! viewport || ! page || ! cards.length) {
            return;
        }

        let activePage = 0;
        let itemsPerPage = 5;

        const getItemsPerPage = () => {
            if (mobileQuery.matches) {
                return 2;
            }

            if (tabletQuery.matches) {
                return 3;
            }

            return 5;
        };

        const setButtons = (buttons, isEnabled) => {
            buttons.forEach((button) => {
                button.hidden = ! isEnabled;
                button.disabled = ! isEnabled;
                button.setAttribute('aria-disabled', isEnabled ? 'false' : 'true');
            });
        };

        const setPage = (pageIndex) => {
            itemsPerPage = getItemsPerPage();

            const totalPages = Math.ceil(cards.length / itemsPerPage);
            activePage = Math.max(0, Math.min(pageIndex, totalPages - 1));
            page.style.setProperty('--g3-equipment-visible-items', itemsPerPage);

            const firstCardIndex = Math.min(activePage * itemsPerPage, Math.max(cards.length - itemsPerPage, 0));
            const lastCardIndex = firstCardIndex + itemsPerPage;

            cards.forEach((card, index) => {
                const isActive = index >= firstCardIndex && index < lastCardIndex;

                card.hidden = ! isActive;
                card.classList.toggle('g3-home-equipment__card--page-end', isActive && index === Math.min(lastCardIndex, cards.length) - 1);
                card.setAttribute('aria-hidden', isActive ? 'false' : 'true');
            });

            setButtons(previousButtons, activePage > 0);
            setButtons(nextButtons, activePage < totalPages - 1);
            viewport.scrollLeft = 0;
        };

        previousButtons.forEach((button) => {
            button.addEventListener('click', () => {
                setPage(activePage - 1);
            });
        });

        nextButtons.forEach((button) => {
            button.addEventListener('click', () => {
                setPage(activePage + 1);
            });
        });

        setPage(activePage);

        const refreshPageSize = () => {
            const firstVisibleCardIndex = activePage * itemsPerPage;

            itemsPerPage = getItemsPerPage();
            setPage(Math.floor(firstVisibleCardIndex / itemsPerPage));
        };

        mobileQuery.addEventListener('change', refreshPageSize);
        tabletQuery.addEventListener('change', refreshPageSize);
    });

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
