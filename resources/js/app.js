import './bootstrap';

document.addEventListener('change', (event) => {
    const input = event.target;

    if (!(input instanceof HTMLInputElement) || input.type !== 'file') {
        return;
    }

    const targetSelector = input.dataset.previewTarget;

    if (!targetSelector) {
        return;
    }

    const preview = document.querySelector(targetSelector);

    if (preview) {
        preview.textContent = input.files?.[0]?.name ?? 'No file selected';
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const root = document.documentElement;
    const themeToggles = document.querySelectorAll('[data-theme-toggle]');
    const themeStorageKey = 'rockmould-theme';

    const getStoredTheme = () => {
        try {
            const theme = window.localStorage.getItem(themeStorageKey);
            return theme === 'light' || theme === 'dark' ? theme : null;
        } catch {
            return null;
        }
    };

    const resolveTheme = () => getStoredTheme() ?? 'dark';

    const syncThemeToggle = (theme) => {
        themeToggles.forEach((toggle) => {
            if (!(toggle instanceof HTMLButtonElement)) {
                return;
            }

            const isLight = theme === 'light';
            toggle.setAttribute('aria-pressed', isLight ? 'true' : 'false');
            toggle.setAttribute('aria-label', isLight ? 'Switch to dark theme' : 'Switch to light theme');

            const label = toggle.querySelector('[data-theme-label]');

            if (label) {
                label.textContent = isLight ? 'Light Mode' : 'Dark Mode';
            }
        });
    };

    const applyTheme = (theme, persist = false) => {
        root.dataset.theme = theme;
        root.style.colorScheme = theme;
        syncThemeToggle(theme);

        if (persist) {
            try {
                window.localStorage.setItem(themeStorageKey, theme);
            } catch {
                // Ignore localStorage restrictions.
            }
        }
    };

    applyTheme(resolveTheme());

    themeToggles.forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const nextTheme = root.dataset.theme === 'light' ? 'dark' : 'light';
            applyTheme(nextTheme, true);
        });
    });

    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            document.body.classList.add('is-page-ready');
        });
    });

    document.querySelectorAll('[data-auto-hide]').forEach((element) => {
        window.setTimeout(() => {
            element.classList.add('opacity-0');
            window.setTimeout(() => element.remove(), 300);
        }, 4500);
    });

    const prepareAutoplayVideos = () => {
        document.querySelectorAll('video').forEach((video) => {
            if (!(video instanceof HTMLVideoElement) || video.closest('[data-video-lightbox-modal]')) {
                return;
            }

            video.muted = true;
            video.defaultMuted = true;
            video.autoplay = true;
            video.playsInline = true;
            video.setAttribute('muted', '');
            video.setAttribute('autoplay', '');
            video.setAttribute('playsinline', '');

            if (video.matches('[data-background-video]')) {
                video.loop = true;
                video.defaultMuted = true;
                video.removeAttribute('controls');
                video.setAttribute('loop', '');
            }

            if (!video.hasAttribute('preload')) {
                video.setAttribute('preload', 'auto');
            }

            const playPromise = video.play();

            if (playPromise && typeof playPromise.catch === 'function') {
                playPromise.catch(() => {
                    // Some browsers delay autoplay until the video is visible or interacted with.
                });
            }
        });
    };

    const ensureVideoLightbox = () => {
        let lightbox = document.querySelector('[data-video-lightbox-modal]');

        if (lightbox instanceof HTMLElement) {
            return lightbox;
        }

        lightbox = document.createElement('div');
        lightbox.className = 'video-lightbox';
        lightbox.setAttribute('data-video-lightbox-modal', '');
        lightbox.setAttribute('data-open', 'false');
        lightbox.setAttribute('aria-hidden', 'true');
        lightbox.setAttribute('hidden', '');
        lightbox.innerHTML = `
            <div class="video-lightbox-backdrop" data-video-lightbox-close></div>
            <div class="video-lightbox-panel" role="dialog" aria-modal="true" aria-labelledby="video-lightbox-title">
                <div class="video-lightbox-header">
                    <p id="video-lightbox-title" class="video-lightbox-title">Project video</p>
                    <button type="button" class="video-lightbox-close" data-video-lightbox-close aria-label="Close video">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <video class="video-lightbox-player" controls autoplay muted playsinline preload="auto">
                    <source data-video-lightbox-source>
                </video>
                <p class="video-lightbox-helper">The video starts muted. Use the player controls to unmute, pause, or seek.</p>
            </div>
        `;

        document.body.appendChild(lightbox);

        lightbox.querySelectorAll('[data-video-lightbox-close]').forEach((button) => {
            button.addEventListener('click', closeVideoLightbox);
        });

        return lightbox;
    };

    const closeVideoLightbox = () => {
        const lightbox = document.querySelector('[data-video-lightbox-modal]');

        if (!(lightbox instanceof HTMLElement)) {
            return;
        }

        const player = lightbox.querySelector('video');
        const source = lightbox.querySelector('[data-video-lightbox-source]');

        if (player instanceof HTMLVideoElement) {
            player.pause();
            player.currentTime = 0;
        }

        if (source instanceof HTMLSourceElement) {
            source.removeAttribute('src');
        }

        if (player instanceof HTMLVideoElement) {
            player.load();
        }

        lightbox.setAttribute('data-open', 'false');
        lightbox.setAttribute('aria-hidden', 'true');
        lightbox.setAttribute('hidden', '');
        document.body.classList.remove('overflow-hidden');
        document.documentElement.classList.remove('overflow-hidden');
    };

    const openVideoLightbox = (src, title = 'Project video') => {
        if (!src) {
            return;
        }

        const lightbox = ensureVideoLightbox();
        const player = lightbox.querySelector('video');
        const source = lightbox.querySelector('[data-video-lightbox-source]');
        const titleElement = lightbox.querySelector('#video-lightbox-title');
        const closeButton = lightbox.querySelector('[data-video-lightbox-close]');

        if (!(player instanceof HTMLVideoElement) || !(source instanceof HTMLSourceElement)) {
            return;
        }

        if (titleElement) {
            titleElement.textContent = title || 'Project video';
        }

        source.src = src;
        player.muted = true;
        player.defaultMuted = true;
        player.autoplay = true;
        player.playsInline = true;
        player.setAttribute('muted', '');
        player.setAttribute('autoplay', '');
        player.setAttribute('playsinline', '');
        player.load();

        lightbox.removeAttribute('hidden');
        lightbox.setAttribute('aria-hidden', 'false');
        lightbox.setAttribute('data-open', 'true');
        document.body.classList.add('overflow-hidden');
        document.documentElement.classList.add('overflow-hidden');

        const playPromise = player.play();

        if (playPromise && typeof playPromise.catch === 'function') {
            playPromise.catch(() => {
                // The controls remain available if autoplay is delayed by the browser.
            });
        }

        if (closeButton instanceof HTMLElement) {
            closeButton.focus({ preventScroll: true });
        }
    };

    const videoSourceFromElement = (element) => {
        if (!(element instanceof HTMLElement)) {
            return '';
        }

        const triggerSource = element.dataset.videoSrc;

        if (triggerSource) {
            return triggerSource;
        }

        const video = element instanceof HTMLVideoElement
            ? element
            : element.querySelector('video');

        if (video instanceof HTMLVideoElement) {
            return video.dataset.videoSrc
                || video.currentSrc
                || video.getAttribute('src')
                || video.querySelector('source')?.getAttribute('src')
                || '';
        }

        return '';
    };

    const ensureImageLightbox = () => {
        let lightbox = document.querySelector('[data-image-lightbox-modal]');

        if (lightbox instanceof HTMLElement) {
            return lightbox;
        }

        lightbox = document.createElement('div');
        lightbox.className = 'image-lightbox';
        lightbox.setAttribute('data-image-lightbox-modal', '');
        lightbox.setAttribute('data-open', 'false');
        lightbox.setAttribute('aria-hidden', 'true');
        lightbox.setAttribute('hidden', '');
        lightbox.innerHTML = `
            <div class="image-lightbox-backdrop" data-image-lightbox-close></div>
            <div class="image-lightbox-panel" role="dialog" aria-modal="true" aria-labelledby="image-lightbox-title">
                <div class="image-lightbox-header">
                    <p id="image-lightbox-title" class="image-lightbox-title">Project image</p>
                    <button type="button" class="image-lightbox-close" data-image-lightbox-close aria-label="Close image preview">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="image-lightbox-frame">
                    <img class="image-lightbox-image" alt="">
                </div>
                <p class="image-lightbox-helper">Click outside the image or press Escape to close this preview.</p>
            </div>
        `;

        document.body.appendChild(lightbox);

        lightbox.querySelectorAll('[data-image-lightbox-close]').forEach((button) => {
            button.addEventListener('click', closeImageLightbox);
        });

        return lightbox;
    };

    const closeImageLightbox = () => {
        const lightbox = document.querySelector('[data-image-lightbox-modal]');

        if (!(lightbox instanceof HTMLElement)) {
            return;
        }

        const image = lightbox.querySelector('img');

        if (image instanceof HTMLImageElement) {
            image.removeAttribute('src');
            image.removeAttribute('alt');
        }

        lightbox.setAttribute('data-open', 'false');
        lightbox.setAttribute('aria-hidden', 'true');
        lightbox.setAttribute('hidden', '');
        document.body.classList.remove('overflow-hidden');
        document.documentElement.classList.remove('overflow-hidden');
    };

    const openImageLightbox = (src, title = 'Project image') => {
        if (!src) {
            return;
        }

        const lightbox = ensureImageLightbox();
        const image = lightbox.querySelector('img');
        const titleElement = lightbox.querySelector('#image-lightbox-title');
        const closeButton = lightbox.querySelector('[data-image-lightbox-close]');

        if (!(image instanceof HTMLImageElement)) {
            return;
        }

        if (titleElement) {
            titleElement.textContent = title || 'Project image';
        }

        image.src = src;
        image.alt = title || 'Project image';

        lightbox.removeAttribute('hidden');
        lightbox.setAttribute('aria-hidden', 'false');
        lightbox.setAttribute('data-open', 'true');
        document.body.classList.add('overflow-hidden');
        document.documentElement.classList.add('overflow-hidden');

        if (closeButton instanceof HTMLElement) {
            closeButton.focus({ preventScroll: true });
        }
    };

    const imageSourceFromElement = (element) => {
        if (!(element instanceof HTMLElement)) {
            return '';
        }

        const triggerSource = element.dataset.imageSrc;

        if (triggerSource) {
            return triggerSource;
        }

        const image = element instanceof HTMLImageElement
            ? element
            : element.querySelector('img');

        if (image instanceof HTMLImageElement) {
            return image.dataset.imageSrc
                || image.currentSrc
                || image.getAttribute('src')
                || '';
        }

        return '';
    };

    document.addEventListener('click', (event) => {
        const target = event.target;

        if (!(target instanceof Element) || target.closest('[data-video-lightbox-modal], [data-image-lightbox-modal]')) {
            return;
        }

        const trigger = target.closest('[data-video-lightbox-trigger], [data-video-lightbox], video');

        if (!(trigger instanceof HTMLElement)) {
            return;
        }

        const src = videoSourceFromElement(trigger);

        if (!src) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        const title = trigger.dataset.videoTitle
            || trigger.querySelector('video')?.dataset.videoTitle
            || trigger.getAttribute('aria-label')
            || document.title;

        openVideoLightbox(src, title.replace(/^Play\s+/i, ''));
    });

    document.addEventListener('click', (event) => {
        const target = event.target;

        if (!(target instanceof Element) || target.closest('[data-image-lightbox-modal], [data-video-lightbox-modal]')) {
            return;
        }

        let trigger = target.closest('[data-image-lightbox-trigger]');

        if (!(trigger instanceof HTMLElement)) {
            const image = target.closest('img[data-image-lightbox]');

            if (!(image instanceof HTMLElement) || image.closest('a')) {
                return;
            }

            trigger = image;
        }

        const src = imageSourceFromElement(trigger);

        if (!src) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        const title = trigger.dataset.imageTitle
            || trigger.querySelector('img')?.dataset.imageTitle
            || trigger.getAttribute('aria-label')
            || trigger.getAttribute('alt')
            || document.title;

        openImageLightbox(src, title.replace(/^Preview\s+/i, ''));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeVideoLightbox();
            closeImageLightbox();
        }
    });

    prepareAutoplayVideos();

    const progressBar = document.getElementById('page-progress');

    if (progressBar) {
        window.addEventListener(
            'scroll',
            () => {
                const scrolled = window.scrollY;
                const total = document.documentElement.scrollHeight - window.innerHeight;
                const pct = total > 0 ? Math.round((scrolled / total) * 100) : 0;
                progressBar.style.width = pct + '%';
            },
            { passive: true }
        );
    }

    const header = document.querySelector('[data-site-header]');

    if (header) {
        const syncHeader = () => {
            header.classList.toggle('is-scrolled', window.scrollY > 50);
        };

        syncHeader();
        window.addEventListener('scroll', syncHeader, { passive: true });
    }

    const drawer = document.querySelector('[data-mobile-drawer]');
    const openButton = document.querySelector('[data-mobile-open]');
    const triggerButton = document.querySelector('[data-mobile-trigger]');
    const closeButtons = document.querySelectorAll('[data-mobile-close]');
    const expandButtons = document.querySelectorAll('[data-mobile-expand]');
    const subnavs = document.querySelectorAll('[data-mobile-subnav]');

    if (drawer && openButton) {
        const syncTrigger = (open) => {
            if (triggerButton instanceof HTMLElement) {
                triggerButton.setAttribute('aria-expanded', open ? 'true' : 'false');
            }
        };

        const closeAllSubnavs = () => {
            expandButtons.forEach((button) => {
                if (!(button instanceof HTMLElement)) {
                    return;
                }

                button.setAttribute('aria-expanded', 'false');
            });

            subnavs.forEach((subnav) => {
                if (!(subnav instanceof HTMLElement)) {
                    return;
                }

                subnav.setAttribute('data-open', 'false');
            });
        };

        const setDrawer = (open) => {
            drawer.setAttribute('data-open', open ? 'true' : 'false');
            syncTrigger(open);
            document.body.classList.toggle('overflow-hidden', open);
            document.body.classList.toggle('is-mobile-drawer-open', open);
            document.documentElement.classList.toggle('overflow-hidden', open);
            document.documentElement.classList.toggle('is-mobile-drawer-open', open);

            if (!open) {
                window.setTimeout(closeAllSubnavs, 220);
            }
        };

        openButton.addEventListener('click', () => setDrawer(true));

        closeButtons.forEach((button) => {
            button.addEventListener('click', () => setDrawer(false));
        });

        expandButtons.forEach((button) => {
            button.addEventListener('click', () => {
                if (!(button instanceof HTMLElement)) {
                    return;
                }

                const target = button.dataset.target;

                if (!target) {
                    return;
                }

                const current = document.getElementById(target);

                if (!(current instanceof HTMLElement)) {
                    return;
                }

                const isOpen = current.getAttribute('data-open') === 'true';

                expandButtons.forEach((otherButton) => {
                    if (!(otherButton instanceof HTMLElement)) {
                        return;
                    }

                    if (otherButton !== button) {
                        otherButton.setAttribute('aria-expanded', 'false');
                    }
                });

                subnavs.forEach((subnav) => {
                    if (!(subnav instanceof HTMLElement)) {
                        return;
                    }

                    if (subnav !== current) {
                        subnav.setAttribute('data-open', 'false');
                    }
                });

                button.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
                current.setAttribute('data-open', isOpen ? 'false' : 'true');
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                setDrawer(false);
            }
        });
    }

    const sidebarDrawer = document.querySelector('[data-sidebar-drawer]');
    const sidebarOpenBtn = document.querySelector('[data-sidebar-open]');
    const sidebarCloseBtns = document.querySelectorAll('[data-sidebar-close]');

    if (sidebarDrawer) {
        const setSidebar = (open) => {
            sidebarDrawer.setAttribute('data-open', open ? 'true' : 'false');
            document.body.classList.toggle('overflow-hidden', open);
        };

        sidebarOpenBtn?.addEventListener('click', () => setSidebar(true));

        sidebarCloseBtns.forEach((button) => {
            button.addEventListener('click', () => setSidebar(false));
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                setSidebar(false);
            }
        });
    }

    const portalShell = document.querySelector('[data-portal-shell]');
    const portalToggle = document.querySelector('[data-portal-toggle]');

    if (portalShell && portalToggle) {
        const storageKey = 'rockmould-portal-collapsed';

        const setCollapsed = (collapsed) => {
            portalShell.setAttribute('data-collapsed', collapsed ? 'true' : 'false');

            try {
                window.localStorage.setItem(storageKey, collapsed ? 'true' : 'false');
            } catch {
                // Ignore localStorage restrictions.
            }
        };

        let initialCollapsed = false;

        try {
            initialCollapsed = window.localStorage.getItem(storageKey) === 'true';
        } catch {
            initialCollapsed = false;
        }

        setCollapsed(initialCollapsed);

        portalToggle.addEventListener('click', () => {
            setCollapsed(portalShell.getAttribute('data-collapsed') !== 'true');
        });
    }

    const adminShell = document.querySelector('[data-admin-shell]');
    const adminToggle = document.querySelector('[data-admin-toggle]');

    if (adminShell && adminToggle) {
        const storageKey = 'rockmould-admin-collapsed';

        const setCollapsed = (collapsed) => {
            adminShell.setAttribute('data-collapsed', collapsed ? 'true' : 'false');

            try {
                window.localStorage.setItem(storageKey, collapsed ? 'true' : 'false');
            } catch {
                // Ignore localStorage restrictions.
            }
        };

        let initialCollapsed = false;

        try {
            initialCollapsed = window.localStorage.getItem(storageKey) === 'true';
        } catch {
            initialCollapsed = false;
        }

        setCollapsed(initialCollapsed);

        adminToggle.addEventListener('click', () => {
            setCollapsed(adminShell.getAttribute('data-collapsed') !== 'true');
        });
    }

    const backToTop = document.getElementById('back-to-top');

    if (backToTop) {
        const syncVisibility = () => {
            backToTop.classList.toggle('is-visible', window.scrollY > 400);
        };

        syncVisibility();
        window.addEventListener('scroll', syncVisibility, { passive: true });

        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');

    if (revealElements.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add('is-visible');

                    const revealChildren = entry.target.querySelectorAll('[data-reveal-item]');

                    revealChildren.forEach((child, index) => {
                        if (!(child instanceof HTMLElement)) {
                            return;
                        }

                        child.style.transitionDelay = `${Math.min(index * 90, 420)}ms`;
                        child.classList.add('is-visible');
                    });

                    observer.unobserve(entry.target);
                });
            },
            { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
        );

        revealElements.forEach((element) => observer.observe(element));
    }

    const animateCounters = () => {
        document.querySelectorAll('[data-counter]').forEach((element) => {
            if (!(element instanceof HTMLElement) || element.dataset.animated) {
                return;
            }

            const target = parseFloat(element.dataset.counter ?? '0');

            if (Number.isNaN(target)) {
                return;
            }

            const duration = 1500;
            const startTime = performance.now();
            const isDecimal = (element.dataset.counter ?? '').includes('.');

            element.dataset.animated = 'true';

            const tick = (now) => {
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = target * eased;

                element.textContent = isDecimal
                    ? current.toFixed(1)
                    : Math.round(current).toLocaleString();

                if (progress < 1) {
                    requestAnimationFrame(tick);
                }
            };

            requestAnimationFrame(tick);
        });
    };

    if ('IntersectionObserver' in window) {
        const counterObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        animateCounters();
                    }
                });
            },
            { threshold: 0.3 }
        );

        document.querySelectorAll('[data-counter]').forEach((element) => {
            counterObserver.observe(element);
        });
    }

    const consultationRoot = document.querySelector('[data-consultation-booking]');

    if (consultationRoot instanceof HTMLElement) {
        const availabilityUrl = consultationRoot.dataset.availabilityUrl;
        const typeInput = consultationRoot.querySelector('[data-consultation-type-input]');
        const dateInput = consultationRoot.querySelector('[data-consultation-date-input]');
        const timeInput = consultationRoot.querySelector('[data-consultation-time-input]');
        const timezoneInput = consultationRoot.querySelector('[data-consultation-timezone-input]');
        const calendarContainer = consultationRoot.querySelector('[data-consultation-calendar]');
        const slotsContainer = consultationRoot.querySelector('[data-consultation-slots]');
        const panels = consultationRoot.querySelectorAll('[data-consultation-panel]');
        const summaryType = consultationRoot.querySelector('[data-summary-type]');
        const summaryDate = consultationRoot.querySelector('[data-summary-date]');
        const summaryTime = consultationRoot.querySelector('[data-summary-time]');
        const summaryTimezone = consultationRoot.querySelector('[data-summary-timezone]');
        const progressSteps = consultationRoot.querySelectorAll('[data-progress-step]');
        const panelMap = {
            type: consultationRoot.querySelector('[data-consultation-panel="type"]'),
            date: consultationRoot.querySelector('[data-consultation-panel="date"]'),
            slot: consultationRoot.querySelector('[data-consultation-panel="slot"]'),
            details: consultationRoot.querySelector('[data-consultation-panel="details"]'),
        };

        const state = {
            type: typeInput instanceof HTMLInputElement ? typeInput.value : '',
            date: dateInput instanceof HTMLInputElement ? dateInput.value : '',
            time: timeInput instanceof HTMLInputElement ? timeInput.value : '',
        };

        const typeLabels = new Map(
            Array.from(consultationRoot.querySelectorAll('[data-consultation-type]')).map((button) => [
                button instanceof HTMLElement ? button.dataset.value ?? '' : '',
                button.querySelector('.consultation-type-title')?.textContent?.trim() ?? '',
            ])
        );

        const formatDate = (value) => {
            if (!value) {
                return 'Choose a date';
            }

            const date = new Date(`${value}T12:00:00`);

            if (Number.isNaN(date.getTime())) {
                return value;
            }

            return new Intl.DateTimeFormat('en-NG', {
                weekday: 'short',
                month: 'short',
                day: 'numeric',
                year: 'numeric',
            }).format(date);
        };

        const formatTime = (value) => {
            if (!value) {
                return 'Choose a slot';
            }

            const [hours, minutes] = value.split(':');
            const date = new Date();
            date.setHours(Number(hours), Number(minutes), 0, 0);

            return new Intl.DateTimeFormat('en-NG', {
                hour: 'numeric',
                minute: '2-digit',
            }).format(date);
        };

        const syncSummaries = () => {
            if (summaryType) {
                summaryType.textContent = typeLabels.get(state.type) || 'Not selected yet';
            }

            if (summaryDate) {
                summaryDate.textContent = formatDate(state.date);
            }

            if (summaryTime) {
                const selectedSlot = slotsContainer?.querySelector(`[data-consultation-slot][data-start="${state.time}"]`);
                summaryTime.textContent = selectedSlot instanceof HTMLElement
                    ? selectedSlot.dataset.end
                        ? `${formatTime(state.time)} - ${formatTime(selectedSlot.dataset.end)}`
                        : formatTime(state.time)
                    : formatTime(state.time);
            }

            if (summaryTimezone && timezoneInput instanceof HTMLInputElement) {
                summaryTimezone.textContent = timezoneInput.value || 'Africa/Lagos';
            }
        };

        const syncPanels = () => {
            panels.forEach((panel) => {
                if (!(panel instanceof HTMLElement)) {
                    return;
                }

                const key = panel.dataset.consultationPanel;

                if (key === 'date') {
                    panel.classList.toggle('is-locked', !state.type);
                }

                if (key === 'slot') {
                    panel.classList.toggle('is-locked', !state.date);
                }

                if (key === 'details') {
                    panel.classList.toggle('is-locked', !state.time);
                }
            });
        };

        const syncProgress = () => {
            const activeIndex = !state.type ? 0 : !state.date ? 1 : !state.time ? 2 : 3;

            progressSteps.forEach((step, index) => {
                if (!(step instanceof HTMLElement)) {
                    return;
                }

                step.dataset.state = index < activeIndex ? 'complete' : index === activeIndex ? 'active' : 'upcoming';
            });
        };

        const focusPanel = (key) => {
            const panel = panelMap[key];

            if (!(panel instanceof HTMLElement)) {
                return;
            }

            window.requestAnimationFrame(() => {
                panel.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
            });
        };

        const bindTypeButtons = () => {
            consultationRoot.querySelectorAll('[data-consultation-type]').forEach((button) => {
                button.addEventListener('click', () => {
                    if (!(button instanceof HTMLElement)) {
                        return;
                    }

                    state.type = button.dataset.value ?? '';

                    if (typeInput instanceof HTMLInputElement) {
                        typeInput.value = state.type;
                    }

                    consultationRoot.querySelectorAll('[data-consultation-type]').forEach((item) => {
                        item.classList.toggle('is-selected', item === button);
                    });

                    syncPanels();
                    syncProgress();
                    syncSummaries();
                    focusPanel('date');
                });
            });
        };

        const bindSlotButtons = () => {
            slotsContainer?.querySelectorAll('[data-consultation-slot]').forEach((button) => {
                button.addEventListener('click', () => {
                    if (!(button instanceof HTMLElement)) {
                        return;
                    }

                    state.time = button.dataset.start ?? '';

                    if (timeInput instanceof HTMLInputElement) {
                        timeInput.value = state.time;
                    }

                    slotsContainer.querySelectorAll('[data-consultation-slot]').forEach((item) => {
                        item.classList.toggle('is-selected', item === button);
                    });

                    syncPanels();
                    syncProgress();
                    syncSummaries();
                    focusPanel('details');
                });
            });
        };

        const fetchAvailability = async (month, selectedDate = '') => {
            if (!availabilityUrl || !(calendarContainer instanceof HTMLElement) || !(slotsContainer instanceof HTMLElement)) {
                return;
            }

            const url = new URL(availabilityUrl, window.location.origin);

            if (month) {
                url.searchParams.set('month', month);
            }

            if (selectedDate) {
                url.searchParams.set('date', selectedDate);
            }

            calendarContainer.classList.add('opacity-60');
            slotsContainer.classList.add('opacity-60');
            slotsContainer.innerHTML = `
                <div class="rounded-2xl border border-border bg-card/60 px-5 py-8 text-center text-sm text-muted-foreground">
                    Loading available times...
                </div>
            `;

            try {
                const response = await window.fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error('Unable to load consultation availability.');
                }

                const payload = await response.json();
                calendarContainer.innerHTML = payload.calendar;
                slotsContainer.innerHTML = payload.slots;

                bindCalendar();
                bindSlotButtons();
            } catch (error) {
                slotsContainer.innerHTML = `
                    <div class="rounded-2xl border border-destructive/30 bg-destructive/10 px-5 py-6 text-sm text-destructive">
                        We could not load the available times right now. Please refresh the page and try again.
                    </div>
                `;
            } finally {
                calendarContainer.classList.remove('opacity-60');
                slotsContainer.classList.remove('opacity-60');
                syncSummaries();
                syncPanels();
                syncProgress();
            }
        };

        const bindCalendar = () => {
            calendarContainer?.querySelectorAll('[data-calendar-nav]').forEach((button) => {
                button.addEventListener('click', () => {
                    if (!(button instanceof HTMLElement)) {
                        return;
                    }

                    const targetMonth = button.dataset.month ?? '';
                    const selectedDate = state.date.startsWith(targetMonth) ? state.date : '';

                    if (!selectedDate) {
                        state.date = '';
                        state.time = '';

                        if (dateInput instanceof HTMLInputElement) {
                            dateInput.value = '';
                        }

                        if (timeInput instanceof HTMLInputElement) {
                            timeInput.value = '';
                        }
                    }

                    fetchAvailability(targetMonth, selectedDate);
                });
            });

            calendarContainer?.querySelectorAll('[data-calendar-day]').forEach((button) => {
                button.addEventListener('click', () => {
                    if (!(button instanceof HTMLElement)) {
                        return;
                    }

                    state.date = button.dataset.date ?? '';
                    state.time = '';

                    if (dateInput instanceof HTMLInputElement) {
                        dateInput.value = state.date;
                    }

                    if (timeInput instanceof HTMLInputElement) {
                        timeInput.value = '';
                    }

                    fetchAvailability(state.date.slice(0, 7), state.date);
                    focusPanel('slot');
                });
            });
        };

        if (timezoneInput instanceof HTMLInputElement) {
            const browserTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

            if (browserTimezone) {
                timezoneInput.value = browserTimezone;
            }
        }

        bindTypeButtons();
        bindCalendar();
        bindSlotButtons();
        syncSummaries();
        syncPanels();
        syncProgress();
    }

    document.addEventListener('click', () => {
        // Reserved for future dropdown interactions.
    });

    // Footer groups builder
    const footerForm = document.querySelector('[data-footer-form]');
    if (footerForm) {
        const container = footerForm.querySelector('[data-footer-groups]');
        const output = footerForm.querySelector('[data-groups-output]');
        const addGroupBtn = footerForm.querySelector('[data-add-group]');

        const serialize = () => {
            const groups = [];
            container.querySelectorAll('[data-group]').forEach((groupEl) => {
                const title = groupEl.querySelector('[data-group-title]').value.trim();
                const links = [];
                groupEl.querySelectorAll('[data-link]').forEach((linkEl) => {
                    const label = linkEl.querySelector('[data-link-label]').value.trim();
                    const url = linkEl.querySelector('[data-link-url]').value.trim();
                    if (label && url) links.push({ label, url });
                });
                if (title) groups.push({ title, links });
            });
            output.value = JSON.stringify(groups);
        };

        const addLink = (groupEl, data = {}) => {
            const linkList = groupEl.querySelector('[data-link-list]');
            const div = document.createElement('div');
            div.setAttribute('data-link', '');
            div.className = 'flex gap-2 items-center';
            div.innerHTML = `
                <input data-link-label placeholder="Label" value="${(data.label || '').replace(/"/g, '&quot;')}" class="input-shell flex-1">
                <input data-link-url placeholder="URL (e.g. /about)" value="${(data.url || '').replace(/"/g, '&quot;')}" class="input-shell flex-1">
                <button type="button" data-remove-link class="text-xs text-destructive hover:underline">✕</button>
            `;
            div.querySelector('[data-remove-link]').addEventListener('click', () => { div.remove(); serialize(); });
            div.querySelectorAll('input').forEach((el) => el.addEventListener('input', serialize));
            linkList.appendChild(div);
        };

        const addGroup = (data = {}) => {
            const div = document.createElement('div');
            div.setAttribute('data-group', '');
            div.className = 'rounded-lg border border-border bg-background/50 p-4 space-y-3';
            div.innerHTML = `
                <div class="flex items-center justify-between">
                    <input data-group-title placeholder="Group title (e.g. Company)" value="${(data.title || '').replace(/"/g, '&quot;')}" class="input-shell flex-1 mr-2">
                    <button type="button" data-remove-group class="text-xs text-destructive hover:underline">Remove Group</button>
                </div>
                <div data-link-list class="space-y-2"></div>
                <button type="button" data-add-link class="text-xs text-primary hover:underline">+ Add Link</button>
            `;
            div.querySelector('[data-remove-group]').addEventListener('click', () => { div.remove(); serialize(); });
            div.querySelector('[data-group-title]').addEventListener('input', serialize);
            div.querySelector('[data-add-link]').addEventListener('click', () => { addLink(div); serialize(); });
            container.appendChild(div);
            (data.links || []).forEach((l) => addLink(div, l));
        };

        let groups = [];
        try { groups = JSON.parse(container.dataset.initial || '[]'); } catch(e) {}
        groups.forEach((g) => addGroup(g));
        if (!groups.length) addGroup({ title: '', links: [{ label: '', url: '' }] });
        addGroupBtn.addEventListener('click', () => { addGroup({ title: '', links: [{ label: '', url: '' }] }); serialize(); });
        serialize();
        footerForm.addEventListener('submit', () => serialize());
    }

    // Hero title lines builder
    const heroContainer = document.querySelector('[data-hero-lines]');
    if (heroContainer) {
        const output = document.querySelector('[data-hero-lines-output]');
        const addBtn = document.querySelector('[data-hero-add-line]');
        const sizes = ['text-sm', 'text-base', 'text-lg', 'text-xl', 'text-2xl', 'text-3xl', 'text-4xl', 'text-5xl', 'text-6xl', 'text-7xl', 'text-8xl', 'text-9xl'];

        const serialize = () => {
            const lines = [];
            heroContainer.querySelectorAll('[data-hero-line]').forEach((row) => {
                const text = row.querySelector('[data-line-text]').value.trim();
                const size = row.querySelector('[data-line-size]').value;
                if (text) lines.push({ text, size });
            });
            output.value = JSON.stringify(lines);
        };

        const addLine = (data = {}) => {
            const row = document.createElement('div');
            row.setAttribute('data-hero-line', '');
            row.className = 'flex gap-2 items-center';
            row.innerHTML = `
                <input data-line-text placeholder="Line text" value="${(data.text || '').replace(/"/g, '&quot;')}" class="input-shell flex-1">
                <select data-line-size class="input-shell w-32">
                    ${sizes.map(s => `<option value="${s}" ${s === (data.size || 'text-5xl') ? 'selected' : ''}>${s.replace('text-', '').toUpperCase()}</option>`).join('')}
                </select>
                <button type="button" data-remove-line class="text-xs text-destructive hover:underline">✕</button>
            `;
            row.querySelector('[data-remove-line]').addEventListener('click', () => { row.remove(); serialize(); });
            row.querySelectorAll('input, select').forEach((el) => el.addEventListener('input', serialize));
            heroContainer.appendChild(row);
        };

        let initial = [];
        try { initial = JSON.parse(heroContainer.dataset.initial || '[]'); } catch (e) {}
        initial.forEach((l) => addLine(l));
        if (!initial.length) addLine({ text: '', size: 'text-5xl' });
        addBtn.addEventListener('click', () => { addLine(); serialize(); });
        serialize();
    }

    // Blog content builder
    document.querySelectorAll('[data-blog-form]').forEach((form) => {
        const builder = form.querySelector('[data-content-builder]');
        const output = form.querySelector('[data-content-output]');
        const addBtn = form.querySelector('[data-add-section]');
        if (!builder || !output || !addBtn) return;

        const createSection = (data = {}) => {
            const div = document.createElement('div');
            div.className = 'rounded-lg border border-border bg-background/50 p-4 space-y-3';
            div.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Section</span>
                    <button type="button" data-remove-section class="text-xs text-destructive hover:underline">Remove</button>
                </div>
                <input data-s-heading placeholder="Section heading" value="${(data.heading || '').replace(/"/g, '&quot;')}" class="input-shell w-full">
                <textarea data-s-paragraphs rows="5" placeholder="Write your paragraphs here. Press Enter twice for a new paragraph." class="input-shell w-full">${(data.paragraphs || []).join('\n\n')}</textarea>
                <div>
                    <label class="text-xs text-muted-foreground">Image or Video URL (optional)</label>
                    <input data-s-media placeholder="Paste image/video URL" value="${(data.media || '').replace(/"/g, '&quot;')}" class="input-shell w-full mt-1">
                </div>
                <input data-s-media-caption placeholder="Caption (optional)" value="${(data.media_caption || '').replace(/"/g, '&quot;')}" class="input-shell w-full">
            `;
            div.querySelector('[data-remove-section]').addEventListener('click', () => { div.remove(); serialize(); });
            builder.appendChild(div);
            div.querySelectorAll('input, textarea').forEach((el) => el.addEventListener('input', serialize));
        };

        const serialize = () => {
            const sections = [];
            builder.querySelectorAll(':scope > div').forEach((div) => {
                const heading = div.querySelector('[data-s-heading]').value.trim();
                const rawText = div.querySelector('[data-s-paragraphs]').value.trim();
                const media = div.querySelector('[data-s-media]').value.trim();
                const mediaCaption = div.querySelector('[data-s-media-caption]').value.trim();
                const paragraphs = rawText ? rawText.split(/\n\s*\n/).map(p => p.trim()).filter(Boolean) : [];
                if (!heading && !paragraphs.length && !media) return;
                const section = { heading, paragraphs };
                if (media) section.media = media;
                if (mediaCaption) section.media_caption = mediaCaption;
                sections.push(section);
            });
            output.value = JSON.stringify(sections);
        };

        let initial = [];
        try { initial = JSON.parse(builder.dataset.initial || '[]'); } catch (e) {}
        initial.forEach((s) => createSection(s));
        addBtn.addEventListener('click', () => { createSection(); serialize(); });
        serialize();

        form.addEventListener('submit', () => serialize());
    });

    const starRating = document.getElementById('star-rating');
    if (starRating) {
        const ratingInput = document.getElementById('rating-input');
        const stars = starRating.querySelectorAll('[data-star]');
        const update = (value) => {
            stars.forEach((btn) => {
                btn.classList.toggle('text-primary', parseInt(btn.dataset.star) <= value);
                btn.classList.toggle('text-muted-foreground/30', parseInt(btn.dataset.star) > value);
            });
            ratingInput.value = value;
        };
        stars.forEach((btn) => btn.addEventListener('click', () => update(parseInt(btn.dataset.star))));
        if (ratingInput.value) update(parseInt(ratingInput.value));
    }
});
