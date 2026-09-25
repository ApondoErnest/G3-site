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

    document.querySelectorAll('[data-contact-centres]').forEach((section) => {
        const buttons = Array.from(section.querySelectorAll('[data-contact-view-button]'));

        if (! buttons.length) {
            return;
        }

        const activateView = (target) => {
            section.dataset.contactView = target;

            buttons.forEach((button) => {
                const isActive = button.dataset.contactViewTarget === target;

                button.classList.toggle('g3-contact-centres__switch-button--active', isActive);
                button.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });
        };

        buttons.forEach((button) => {
            button.addEventListener('click', () => activateView(button.dataset.contactViewTarget ?? 'map'));
        });
    });

    document.querySelectorAll('[data-contact-message]').forEach((section) => {
        const textarea = section.querySelector('[data-contact-message-text]');
        const counter = section.querySelector('[data-contact-message-count]');

        if (! textarea || ! counter) {
            return;
        }

        const maxLength = Number(textarea.getAttribute('maxlength') ?? 1000);
        const updateCounter = () => {
            counter.textContent = `${textarea.value.length}/${maxLength}`;
        };

        textarea.addEventListener('input', updateCounter);
        updateCounter();
    });

    document.querySelectorAll('[data-contact-form]').forEach((form) => {
        const status = form.querySelector('[data-contact-status]');
        const alertBox = form.querySelector('[data-contact-alert]');
        const submitButton = form.querySelector('[data-contact-submit]');
        const fields = ['name', 'phone', 'email', 'centre', 'subject', 'message'];

        const clearFieldErrors = () => {
            fields.forEach((name) => {
                const input = form.querySelector(`[name="${name}"]`);
                const error = form.querySelector(`[data-contact-error="${name}"]`);

                input?.removeAttribute('aria-invalid');

                if (error) {
                    error.hidden = true;
                    error.textContent = '';
                }
            });

            if (alertBox) {
                alertBox.hidden = true;
                const paragraph = alertBox.querySelector('p');

                if (paragraph) {
                    paragraph.textContent = '';
                }
            }
        };

        const showFieldError = (name, message) => {
            const input = form.querySelector(`[name="${name}"]`);
            const error = form.querySelector(`[data-contact-error="${name}"]`);

            input?.setAttribute('aria-invalid', 'true');

            if (error) {
                error.hidden = false;
                error.textContent = message;
            }
        };

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            clearFieldErrors();

            if (status) {
                status.hidden = true;
                status.textContent = '';
            }

            let invalid = false;

            fields.forEach((name) => {
                const input = form.querySelector(`[name="${name}"]`);

                if (! input) {
                    return;
                }

                const value = input.value.trim();
                const empty = value === '';
                const invalidEmail = input.type === 'email' && value !== '' && ! input.checkValidity();

                if (! empty && ! invalidEmail) {
                    return;
                }

                invalid = true;
                showFieldError(name, invalidEmail ? input.dataset.invalidMessage : input.dataset.requiredMessage);
            });

            if (invalid) {
                form.querySelector('[aria-invalid="true"]')?.focus();

                return;
            }

            if (submitButton) {
                submitButton.disabled = true;
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: new FormData(form),
                });

                const payload = await response.json().catch(() => ({}));

                if (! response.ok) {
                    const errors = payload.errors ?? {};

                    Object.entries(errors).forEach(([name, fieldMessages]) => {
                        const message = Array.isArray(fieldMessages) ? fieldMessages[0] : fieldMessages;

                        if (form.querySelector(`[data-contact-error="${name}"]`)) {
                            showFieldError(name, message);

                            return;
                        }

                        if (alertBox) {
                            alertBox.hidden = false;
                            const paragraph = alertBox.querySelector('p');

                            if (paragraph) {
                                paragraph.textContent = message;
                            }
                        }
                    });

                    form.querySelector('[aria-invalid="true"]')?.focus();

                    return;
                }

                form.reset();
                form.querySelector('[data-contact-message-text]')?.dispatchEvent(new Event('input'));

                if (status) {
                    status.hidden = false;
                    status.textContent = payload.message ?? '';
                }
            } catch {
                if (alertBox) {
                    alertBox.hidden = false;
                    const paragraph = alertBox.querySelector('p');

                    if (paragraph) {
                        paragraph.textContent = submitButton?.dataset.formError ?? '';
                    }
                }
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                }
            }
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

    document.querySelectorAll('[data-tariff-navigator]').forEach((section) => {
        const profiles = Array.from(section.querySelectorAll('[data-tariff-profile]'));
        const passports = Array.from(section.querySelectorAll('[data-tariff-passport]'));
        const assistant = section.querySelector('[data-tariff-assistant]');
        const assistantToggle = section.querySelector('[data-tariff-assistant-toggle]');
        const assistantOptions = Array.from(section.querySelectorAll('[data-tariff-assistant-select]'));

        if (! profiles.length || ! passports.length) {
            return;
        }

        const activateTariff = (target) => {
            profiles.forEach((profile) => {
                const isActive = profile.dataset.tariffTarget === target;

                profile.classList.toggle('g3-tariff-finder__profile--active', isActive);
                profile.setAttribute('aria-selected', isActive ? 'true' : 'false');
                profile.tabIndex = isActive ? 0 : -1;
            });

            passports.forEach((passport) => {
                passport.hidden = passport.dataset.tariffKey !== target;
            });
        };

        profiles.forEach((profile, index) => {
            profile.addEventListener('click', () => activateTariff(profile.dataset.tariffTarget));

            profile.addEventListener('keydown', (event) => {
                const keyIndexMap = {
                    ArrowDown: (index + 1) % profiles.length,
                    ArrowRight: (index + 1) % profiles.length,
                    ArrowLeft: (index - 1 + profiles.length) % profiles.length,
                    ArrowUp: (index - 1 + profiles.length) % profiles.length,
                    End: profiles.length - 1,
                    Home: 0,
                };

                if (! (event.key in keyIndexMap)) {
                    return;
                }

                event.preventDefault();
                profiles[keyIndexMap[event.key]].focus();
                activateTariff(profiles[keyIndexMap[event.key]].dataset.tariffTarget);
            });
        });

        assistantToggle?.addEventListener('click', () => {
            if (! assistant) {
                return;
            }

            const isHidden = assistant.hidden;

            assistant.hidden = ! isHidden;
            assistantToggle.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
        });

        assistantOptions.forEach((option) => {
            option.addEventListener('click', () => {
                activateTariff(option.dataset.tariffTarget);
                assistant?.setAttribute('hidden', '');
                assistantToggle?.setAttribute('aria-expanded', 'false');
            });
        });

        activateTariff(profiles.find((profile) => profile.getAttribute('aria-selected') === 'true')?.dataset.tariffTarget ?? profiles[0].dataset.tariffTarget);
    });

    document.querySelectorAll('[data-appointment-hub]').forEach((section) => {
        const modeTabs = Array.from(section.querySelectorAll('[data-appointment-mode]'));
        const panels = Array.from(section.querySelectorAll('[data-appointment-panel]'));
        const benefits = section.querySelector('[data-appointment-benefits]');
        const centreButtons = Array.from(section.querySelectorAll('[data-appointment-centre]'));
        const centreIdInput = section.querySelector('[data-appointment-centre-id]');
        const categoryIdInput = section.querySelector('[data-appointment-category-id]');
        const periodInput = section.querySelector('[data-appointment-period-value]');
        const periodButtons = Array.from(section.querySelectorAll('[data-appointment-period]'));
        const serviceSelect = section.querySelector('[data-appointment-service]');
        const categorySelect = section.querySelector('[data-appointment-category]');
        const datePickers = Array.from(section.querySelectorAll('[data-appointment-date-picker]'));
        const summaryCentre = section.querySelector('[data-appointment-summary-centre]');
        const summaryService = section.querySelector('[data-appointment-summary-service]');
        const summaryCategory = section.querySelector('[data-appointment-summary-category]');
        const summaryTariff = section.querySelector('[data-appointment-summary-tariff]');

        if (! modeTabs.length || ! panels.length) {
            return;
        }

        section.querySelectorAll('[data-appointment-form]').forEach((form) => {
            const status = form.querySelector('[data-form-status]');
            const statusMessage = form.querySelector('[data-form-status-message]');
            const statusReference = form.querySelector('[data-form-status-reference]');
            const statusDetail = form.querySelector('[data-form-status-detail]');
            const alertBox = form.querySelector('[data-form-alert]');
            const alertMessage = form.querySelector('[data-form-alert-message]');
            const submitButton = form.querySelector('[type="submit"]');

            const visibleControl = (name) => {
                if (name === 'preferred_date') {
                    return form.querySelector('[data-appointment-date-trigger]');
                }

                if (name === 'vehicle_category_id') {
                    return form.querySelector('[name="vehicle_category"]');
                }

                return form.querySelector(`[name="${name}"]`);
            };

            const clearField = (name) => {
                form.querySelector(`[name="${name}"]`)?.removeAttribute('aria-invalid');
                visibleControl(name)?.removeAttribute('aria-invalid');
                const error = form.querySelector(`[data-field-error="${name}"]`);

                if (error) {
                    error.hidden = true;
                    error.textContent = '';
                }
            };

            const showFieldError = (name, message) => {
                const error = form.querySelector(`[data-field-error="${name}"]`);

                form.querySelector(`[name="${name}"]`)?.setAttribute('aria-invalid', 'true');
                visibleControl(name)?.setAttribute('aria-invalid', 'true');

                if (error) {
                    error.hidden = false;
                    error.textContent = message;

                    return;
                }

                if (alertBox && alertMessage) {
                    alertBox.hidden = false;
                    alertMessage.textContent = message;
                }
            };

            form.addEventListener('input', (event) => {
                if (event.target?.name) {
                    clearField(event.target.name);
                }
            });

            form.addEventListener('change', (event) => {
                if (event.target?.name) {
                    clearField(event.target.name);
                }
            });

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                form.querySelectorAll('[data-field-error]').forEach((error) => clearField(error.dataset.fieldError));

                if (status) {
                    status.hidden = true;
                }

                if (alertBox) {
                    alertBox.hidden = true;
                }

                let invalid = false;

                form.querySelectorAll('[data-required-message]').forEach((input) => {
                    const value = input.value.trim();
                    const selectedDisabled = input instanceof HTMLSelectElement && input.selectedOptions?.[0]?.disabled;

                    if (value !== '' && ! selectedDisabled) {
                        return;
                    }

                    invalid = true;
                    showFieldError(input.name, input.dataset.requiredMessage ?? '');
                });

                const email = form.querySelector('[data-invalid-message]');

                if (email && email.value.trim() !== '' && ! email.checkValidity()) {
                    invalid = true;
                    showFieldError(email.name, email.dataset.invalidMessage ?? '');
                }

                if (invalid) {
                    form.querySelector('[aria-invalid="true"]')?.focus();

                    return;
                }

                if (submitButton) {
                    submitButton.disabled = true;
                }

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(form),
                    });
                    const payload = await response.json().catch(() => ({}));

                    if (! response.ok) {
                        const errors = payload.errors ?? {};

                        Object.entries(errors).forEach(([name, fieldMessages]) => {
                            const message = Array.isArray(fieldMessages) ? fieldMessages[0] : fieldMessages;

                            showFieldError(name, message);
                        });

                        if (Object.keys(errors).length === 0 && alertBox && alertMessage) {
                            alertBox.hidden = false;
                            alertMessage.textContent = payload.message || submitButton?.dataset.formError || '';
                        }

                        form.querySelector('[aria-invalid="true"]')?.focus();

                        return;
                    }

                    if (status && statusMessage) {
                        status.hidden = false;
                        statusMessage.textContent = payload.message ?? '';
                    }

                    if (statusReference) {
                        statusReference.textContent = payload.reference ?? '';
                    }

                    if (statusDetail) {
                        statusDetail.textContent = payload.status
                            ? ` — ${payload.status} — ${payload.centre ?? ''}`
                            : '';
                    }

                    form.reset();

                    if (form.classList.contains('g3-express-pass__form')) {
                        form.querySelector('[data-appointment-date-picker]')?.dispatchEvent(new Event('appointment-date-clear'));

                        const centreId = centreIdInput?.value ?? '';

                        centreButtons.forEach((button) => {
                            const isActive = button.dataset.centreId === centreId;

                            button.classList.toggle('g3-express-pass__centre--active', isActive);
                            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                        });

                        if (summaryCentre) {
                            summaryCentre.textContent = centreButtons.find((button) => button.dataset.centreId === centreId)?.dataset.centreLabel ?? '';
                        }

                        const period = periodInput?.value ?? 'any';

                        periodButtons.forEach((button) => {
                            const isActive = button.dataset.period === period;

                            button.classList.toggle('g3-express-period__active', isActive);
                            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                        });

                        syncCategoryId();
                        syncServiceOptions();
                        updateSummary();

                        const trackingReference = section.querySelector('[name="request_reference"]');

                        if (trackingReference && payload.reference) {
                            trackingReference.value = payload.reference;
                        }
                    }

                    status?.scrollIntoView({ block: 'nearest' });
                } catch {
                    if (alertBox && alertMessage) {
                        alertBox.hidden = false;
                        alertMessage.textContent = submitButton?.dataset.formError ?? '';
                    }
                } finally {
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                }
            });
        });

        const setMode = (target) => {
            modeTabs.forEach((tab) => {
                const isActive = tab.dataset.appointmentTarget === target;

                tab.classList.toggle('g3-appointment-hub__mode-button--active', isActive);
                tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                tab.tabIndex = isActive ? 0 : -1;
            });

            panels.forEach((panel) => {
                panel.hidden = panel.dataset.appointmentPanelKey !== target;
            });

            if (benefits) {
                benefits.hidden = target !== 'booking';
            }
        };

        const selectedOptionText = (select) => select?.selectedOptions?.[0]?.textContent?.trim() ?? '';

        const parseLocalDate = (value) => {
            const match = /^(\d{4})-(\d{2})-(\d{2})$/.exec(value ?? '');

            if (! match) {
                return null;
            }

            return new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3]));
        };

        const toIsoDate = (date) => [
            date.getFullYear(),
            String(date.getMonth() + 1).padStart(2, '0'),
            String(date.getDate()).padStart(2, '0'),
        ].join('-');

        const startOfMonth = (date) => new Date(date.getFullYear(), date.getMonth(), 1);
        const isSameDay = (firstDate, secondDate) => firstDate
            && secondDate
            && firstDate.getFullYear() === secondDate.getFullYear()
            && firstDate.getMonth() === secondDate.getMonth()
            && firstDate.getDate() === secondDate.getDate();

        const isBeforeDay = (firstDate, secondDate) => new Date(
            firstDate.getFullYear(),
            firstDate.getMonth(),
            firstDate.getDate(),
        ) < new Date(
            secondDate.getFullYear(),
            secondDate.getMonth(),
            secondDate.getDate(),
        );

        const addMonths = (date, amount) => new Date(date.getFullYear(), date.getMonth() + amount, 1);

        const updateSummary = () => {
            if (summaryService && serviceSelect) {
                summaryService.textContent = selectedOptionText(serviceSelect);
            }

            if (summaryCategory && categorySelect) {
                const selectedCategory = categorySelect.selectedOptions?.[0];

                summaryCategory.textContent = selectedCategory?.dataset.summary
                    ?? selectedOptionText(categorySelect).split('—')[0].trim();
            }

            if (summaryTariff && categorySelect) {
                summaryTariff.textContent = categorySelect.selectedOptions?.[0]?.dataset.tariff ?? '';
            }
        };

        datePickers.forEach((picker) => {
            const trigger = picker.querySelector('[data-appointment-date-trigger]');
            const calendar = picker.querySelector('[data-appointment-calendar]');
            const title = picker.querySelector('[data-appointment-calendar-title]');
            const previousButton = picker.querySelector('[data-appointment-calendar-prev]');
            const nextButton = picker.querySelector('[data-appointment-calendar-next]');
            const grid = picker.querySelector('[data-appointment-calendar-grid]');
            const display = picker.querySelector('[data-appointment-date-display]');
            const valueInput = picker.querySelector('[data-appointment-date-value]');

            if (! trigger || ! calendar || ! title || ! previousButton || ! nextButton || ! grid || ! display || ! valueInput) {
                return;
            }

            const locale = picker.dataset.locale === 'en' ? 'en-US' : 'fr-FR';
            const today = new Date();
            const minimumDate = parseLocalDate(picker.dataset.minDate) ?? new Date(today.getFullYear(), today.getMonth(), today.getDate());
            const monthFormatter = new Intl.DateTimeFormat(locale, { month: 'long', year: 'numeric' });
            const displayFormatter = new Intl.DateTimeFormat(locale, {
                day: '2-digit',
                month: 'short',
                weekday: 'short',
                year: 'numeric',
            });
            const fullFormatter = new Intl.DateTimeFormat(locale, {
                dateStyle: 'full',
            });

            let selectedDate = parseLocalDate(valueInput.value || valueInput.getAttribute('value'));
            let visibleMonth = startOfMonth(selectedDate ?? minimumDate);

            const closeCalendar = () => {
                calendar.hidden = true;
                trigger.setAttribute('aria-expanded', 'false');
            };

            const openCalendar = () => {
                calendar.hidden = false;
                trigger.setAttribute('aria-expanded', 'true');
                renderCalendar();
            };

            const setSelectedDate = (date) => {
                const selectedValue = toIsoDate(date);

                selectedDate = date;
                picker.dataset.selectedDate = selectedValue;
                valueInput.value = selectedValue;

                display.textContent = displayFormatter.format(date).replace('.', '');
                trigger.classList.add('g3-appointment-date__trigger--selected');
                valueInput.dispatchEvent(new Event('input', { bubbles: true }));
                closeCalendar();
            };

            picker.addEventListener('appointment-date-clear', () => {
                selectedDate = null;
                visibleMonth = startOfMonth(minimumDate);
                delete picker.dataset.selectedDate;
                valueInput.value = '';
                display.textContent = picker.dataset.emptyLabel ?? '';
                trigger.classList.remove('g3-appointment-date__trigger--selected');
                renderCalendar();
            });

            const renderCalendar = () => {
                const monthStart = startOfMonth(visibleMonth);
                const monthEnd = new Date(monthStart.getFullYear(), monthStart.getMonth() + 1, 0);
                const leadingBlanks = (monthStart.getDay() + 6) % 7;
                const minimumMonth = startOfMonth(minimumDate);

                title.textContent = monthFormatter.format(monthStart);
                previousButton.disabled = monthStart <= minimumMonth;
                grid.innerHTML = '';

                for (let index = 0; index < leadingBlanks; index += 1) {
                    const blank = document.createElement('span');

                    blank.className = 'g3-appointment-calendar__blank';
                    grid.append(blank);
                }

                for (let day = 1; day <= monthEnd.getDate(); day += 1) {
                    const date = new Date(monthStart.getFullYear(), monthStart.getMonth(), day);
                    const button = document.createElement('button');
                    const isDisabled = isBeforeDay(date, minimumDate);
                    const isSelected = isSameDay(date, selectedDate);
                    const isToday = isSameDay(date, today);

                    button.type = 'button';
                    button.textContent = String(day);
                    button.className = 'g3-appointment-calendar__day';
                    button.disabled = isDisabled;
                    button.setAttribute('aria-label', fullFormatter.format(date));
                    button.classList.toggle('g3-appointment-calendar__day--selected', isSelected);
                    button.classList.toggle('g3-appointment-calendar__day--today', isToday && ! isSelected);

                    if (! isDisabled) {
                        button.addEventListener('click', () => setSelectedDate(date));
                    }

                    grid.append(button);
                }
            };

            trigger.addEventListener('click', () => {
                if (calendar.hidden) {
                    openCalendar();
                    return;
                }

                closeCalendar();
            });

            previousButton.addEventListener('click', () => {
                visibleMonth = addMonths(visibleMonth, -1);
                renderCalendar();
            });

            nextButton.addEventListener('click', () => {
                visibleMonth = addMonths(visibleMonth, 1);
                renderCalendar();
            });

            picker.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeCalendar();
                    trigger.focus();
                }
            });

            document.addEventListener('click', (event) => {
                if (! picker.contains(event.target)) {
                    closeCalendar();
                }
            });

            renderCalendar();
        });

        modeTabs.forEach((tab, index) => {
            tab.addEventListener('click', () => setMode(tab.dataset.appointmentTarget));

            tab.addEventListener('keydown', (event) => {
                const keyIndexMap = {
                    ArrowDown: (index + 1) % modeTabs.length,
                    ArrowRight: (index + 1) % modeTabs.length,
                    ArrowLeft: (index - 1 + modeTabs.length) % modeTabs.length,
                    ArrowUp: (index - 1 + modeTabs.length) % modeTabs.length,
                    End: modeTabs.length - 1,
                    Home: 0,
                };

                if (! (event.key in keyIndexMap)) {
                    return;
                }

                event.preventDefault();
                modeTabs[keyIndexMap[event.key]].focus();
                setMode(modeTabs[keyIndexMap[event.key]].dataset.appointmentTarget);
            });
        });

        centreButtons.forEach((button) => {
            button.addEventListener('click', () => {
                centreButtons.forEach((centreButton) => {
                    const isActive = centreButton === button;

                    centreButton.classList.toggle('g3-express-pass__centre--active', isActive);
                    centreButton.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                });

                if (summaryCentre) {
                    summaryCentre.textContent = button.dataset.centreLabel ?? '';
                }

                if (centreIdInput) {
                    centreIdInput.value = button.dataset.centreId ?? '';
                }

                syncServiceOptions();
                updateSummary();
            });
        });

        periodButtons.forEach((button) => {
            button.addEventListener('click', () => {
                periodButtons.forEach((periodButton) => {
                    const isActive = periodButton === button;

                    periodButton.classList.toggle('g3-express-period__active', isActive);
                    periodButton.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                });

                if (periodInput) {
                    periodInput.value = button.dataset.period ?? 'any';
                }
            });
        });

        const syncCategoryId = () => {
            if (categoryIdInput && categorySelect) {
                categoryIdInput.value = categorySelect.selectedOptions?.[0]?.dataset.categoryId ?? '';
            }
        };

        const listedIds = (value) => (value ?? '').split(',').filter(Boolean);

        const syncServiceOptions = () => {
            if (! serviceSelect) {
                return;
            }

            const centreId = String(centreIdInput?.value ?? '');
            const categoryId = String(categoryIdInput?.value ?? '');
            const options = Array.from(serviceSelect.options);

            options.forEach((option) => {
                const offered = listedIds(option.dataset.centreIds).includes(centreId)
                    && listedIds(option.dataset.categoryIds).includes(categoryId);

                option.hidden = ! offered;
                option.disabled = ! offered;
            });

            if (serviceSelect.selectedOptions?.[0]?.disabled) {
                const available = options.find((option) => ! option.disabled);

                if (available) {
                    serviceSelect.value = available.value;
                }
            }
        };

        serviceSelect?.addEventListener('change', updateSummary);
        categorySelect?.addEventListener('change', () => {
            syncCategoryId();
            syncServiceOptions();
            updateSummary();
        });
        syncCategoryId();
        syncServiceOptions();

        setMode(modeTabs.find((tab) => tab.getAttribute('aria-selected') === 'true')?.dataset.appointmentTarget ?? modeTabs[0].dataset.appointmentTarget);
        updateSummary();
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

    document.querySelectorAll('[data-road-safety-hub]').forEach((section) => {
        const tabs = Array.from(section.querySelectorAll('[data-road-safety-tab]'));
        const panels = Array.from(section.querySelectorAll('[data-road-safety-context-panel]'));
        const detailToggles = Array.from(section.querySelectorAll('[data-road-safety-details-toggle]'));

        if (! tabs.length || ! panels.length) {
            return;
        }

        const activateContext = (target) => {
            tabs.forEach((tab) => {
                const isActive = tab.dataset.roadSafetyTarget === target;

                tab.classList.toggle('g3-road-tabs__button--active', isActive);
                tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                tab.tabIndex = isActive ? 0 : -1;
            });

            panels.forEach((contextPanel) => {
                contextPanel.hidden = contextPanel.dataset.roadSafetyContextPanel !== target;
            });
        };

        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => activateContext(tab.dataset.roadSafetyTarget));

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
                activateContext(tabs[keyIndexMap[event.key]].dataset.roadSafetyTarget);
            });
        });

        detailToggles.forEach((toggle) => {
            toggle.addEventListener('click', () => {
                const card = toggle.closest('[data-road-safety-card]');
                const details = card?.querySelector('[data-road-safety-details]');
                const label = toggle.querySelector('span');

                if (! card || ! details || ! label) {
                    return;
                }

                const isOpen = toggle.getAttribute('aria-expanded') === 'true';

                details.hidden = isOpen;
                toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
                card.classList.toggle('g3-road-card--open', ! isOpen);
                label.textContent = isOpen
                    ? (toggle.dataset.labelOpen ?? label.textContent)
                    : (toggle.dataset.labelClose ?? label.textContent);
            });
        });

        activateContext(tabs.find((tab) => tab.getAttribute('aria-selected') === 'true')?.dataset.roadSafetyTarget ?? tabs[0].dataset.roadSafetyTarget);
    });

    document.querySelectorAll('[data-road-reflex]').forEach((section) => {
        const checks = Array.from(section.querySelectorAll('[data-road-reflex-check]'));
        const status = section.querySelector('[data-road-reflex-status]');
        const guidance = section.querySelector('[data-road-reflex-guidance]');

        if (! checks.length || ! status || ! guidance) {
            return;
        }

        const updateReflex = () => {
            const checkedCount = checks.filter((check) => check.checked).length;
            const isComplete = checkedCount === checks.length;

            checks.forEach((check) => {
                check.closest('[data-road-reflex-card]')?.classList.toggle('g3-road-reflex__card--checked', check.checked);
            });

            section.classList.toggle('g3-road-reflex--complete', isComplete);

            if (checkedCount === 0) {
                status.textContent = status.dataset.default ?? '';
                guidance.textContent = guidance.dataset.default ?? '';

                return;
            }

            const progressLabel = checkedCount === 1
                ? (status.dataset.progressSingular ?? '')
                : (status.dataset.progressPlural ?? '');

            status.textContent = progressLabel.replace(':count', String(checkedCount));
            guidance.textContent = isComplete
                ? (guidance.dataset.complete ?? '')
                : (guidance.dataset.default ?? '');
        };

        checks.forEach((check) => {
            check.addEventListener('change', updateReflex);
        });

        updateReflex();
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
