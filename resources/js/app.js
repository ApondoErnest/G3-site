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

    document.querySelectorAll('[data-centres-live]').forEach((section) => {
        const tabs = Array.from(section.querySelectorAll('[data-centre-tab]'));
        const panels = Array.from(section.querySelectorAll('[data-centre-panel]'));
        const markers = Array.from(section.querySelectorAll('[data-centre-map-marker]'));

        if (! tabs.length || ! panels.length) {
            return;
        }

        const activateCentre = (target) => {
            tabs.forEach((tab) => {
                const isActive = tab.dataset.centreTarget === target;

                tab.classList.toggle('g3-centres-live__tab--active', isActive);
                tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            panels.forEach((panel) => {
                panel.hidden = panel.dataset.centreKey !== target;
            });

            markers.forEach((marker) => {
                const isActive = marker.dataset.centreTarget === target;

                marker.classList.toggle('g3-centres-live__marker--active', isActive);
                marker.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });
        };

        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => activateCentre(tab.dataset.centreTarget));

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
                activateCentre(tabs[keyIndexMap[event.key]].dataset.centreTarget);
            });
        });

        markers.forEach((marker) => {
            marker.addEventListener('click', () => activateCentre(marker.dataset.centreTarget));
        });
    });

    document.querySelectorAll('[data-centre-hero-carousel]').forEach((carousel) => {
        const slides = Array.from(carousel.querySelectorAll('[data-centre-hero-slide]'));
        const dots = Array.from(carousel.querySelectorAll('[data-centre-hero-dot]'));
        const previousButton = carousel.querySelector('[data-centre-hero-prev]');
        const nextButton = carousel.querySelector('[data-centre-hero-next]');

        if (! slides.length || ! dots.length) {
            return;
        }

        let activeIndex = 0;

        const setSlide = (index) => {
            activeIndex = (index + slides.length) % slides.length;

            slides.forEach((slide, slideIndex) => {
                const isActive = slideIndex === activeIndex;

                slide.classList.toggle('g3-centre-hero__slide--active', isActive);
                slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
            });

            dots.forEach((dot, dotIndex) => {
                const isActive = dotIndex === activeIndex;

                dot.classList.toggle('g3-centre-hero__dot--active', isActive);
                if (isActive) {
                    dot.setAttribute('aria-current', 'true');
                } else {
                    dot.removeAttribute('aria-current');
                }
            });
        };

        previousButton?.addEventListener('click', () => setSlide(activeIndex - 1));
        nextButton?.addEventListener('click', () => setSlide(activeIndex + 1));

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => setSlide(index));
        });

        carousel.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                setSlide(activeIndex - 1);
            }

            if (event.key === 'ArrowRight') {
                event.preventDefault();
                setSlide(activeIndex + 1);
            }
        });
    });

    document.querySelectorAll('[data-technical-journey]').forEach((section) => {
        const tabs = Array.from(section.querySelectorAll('[data-technical-step-tab]'));
        const panels = Array.from(section.querySelectorAll('[data-technical-step-panel]'));

        if (! tabs.length || ! panels.length) {
            return;
        }

        const activateStep = (target) => {
            tabs.forEach((tab) => {
                const isActive = tab.dataset.technicalStepTarget === target;

                tab.classList.toggle('g3-technical-journey__step--active', isActive);
                tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                tab.tabIndex = isActive ? 0 : -1;
            });

            panels.forEach((panel) => {
                panel.hidden = panel.dataset.technicalStepKey !== target;
            });
        };

        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => activateStep(tab.dataset.technicalStepTarget));

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
                activateStep(tabs[keyIndexMap[event.key]].dataset.technicalStepTarget);
            });
        });

        activateStep(tabs.find((tab) => tab.getAttribute('aria-selected') === 'true')?.dataset.technicalStepTarget ?? tabs[0].dataset.technicalStepTarget);
    });

    document.querySelectorAll('[data-inspection-control-explorer]').forEach((section) => {
        const tabs = Array.from(section.querySelectorAll('[data-inspection-control-tab]'));
        const panels = Array.from(section.querySelectorAll('[data-inspection-control-panel]'));

        if (! tabs.length || ! panels.length) {
            return;
        }

        const activateControl = (target) => {
            tabs.forEach((tab) => {
                const isActive = tab.dataset.inspectionControlTarget === target;

                tab.classList.toggle('g3-control-explorer__tab--active', isActive);
                tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                tab.tabIndex = isActive ? 0 : -1;
            });

            panels.forEach((panel) => {
                panel.hidden = panel.dataset.inspectionControlKey !== target;
            });
        };

        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => activateControl(tab.dataset.inspectionControlTarget));

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
                activateControl(tabs[keyIndexMap[event.key]].dataset.inspectionControlTarget);
            });
        });

        activateControl(tabs.find((tab) => tab.getAttribute('aria-selected') === 'true')?.dataset.inspectionControlTarget ?? tabs[0].dataset.inspectionControlTarget);
    });

    document.querySelectorAll('[data-visit-preparation]').forEach((section) => {
        const configElement = section.querySelector('[data-visit-preparation-config]');
        const vehicleSelect = section.querySelector('[data-visit-preparation-vehicle]');
        const serviceSelect = section.querySelector('[data-visit-preparation-service]');
        const documentsList = section.querySelector('[data-visit-preparation-documents]');
        const beforeList = section.querySelector('[data-visit-preparation-before]');

        if (! configElement || ! vehicleSelect || ! serviceSelect || ! documentsList || ! beforeList) {
            return;
        }

        let rules = {};

        try {
            rules = JSON.parse(configElement.textContent ?? '{}');
        } catch {
            rules = {};
        }

        const renderList = (list, items) => {
            list.replaceChildren();

            items.forEach((item) => {
                const li = document.createElement('li');
                const marker = document.createElement('span');

                marker.setAttribute('aria-hidden', 'true');
                li.append(marker, document.createTextNode(item));
                list.append(li);
            });
        };

        const resolveRule = () => {
            const serviceRule = serviceSelect.value === 'default' ? null : rules[serviceSelect.value];
            const vehicleRule = vehicleSelect.value === 'default' ? null : rules[vehicleSelect.value];

            return serviceRule ?? vehicleRule ?? rules.default ?? { documents: [], before: [] };
        };

        const updatePreparation = () => {
            const rule = resolveRule();

            renderList(documentsList, rule.documents ?? []);
            renderList(beforeList, rule.before ?? []);
        };

        vehicleSelect.addEventListener('change', updatePreparation);
        serviceSelect.addEventListener('change', updatePreparation);
        updatePreparation();
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
