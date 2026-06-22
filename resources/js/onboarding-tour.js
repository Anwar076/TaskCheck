export function initOnboardingTour(root) {
    if (!root) {
        return;
    }

    let config;
    try {
        config = JSON.parse(root.dataset.tour || '{}');
    } catch {
        return;
    }

    const slides = Array.isArray(config.slides) ? config.slides : [];
    if (slides.length === 0) {
        return;
    }

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let index = 0;
    let open = true;
    let waitTimer = null;
    let repaintTimer = null;
    let activeTarget = null;
    let hiddenTitleElements = [];

    const overlay = root.querySelector('[data-tour-overlay]');
    const backdrop = root.querySelector('[data-tour-backdrop]');
    const masks = {
        top: root.querySelector('[data-tour-mask-top]'),
        left: root.querySelector('[data-tour-mask-left]'),
        right: root.querySelector('[data-tour-mask-right]'),
        bottom: root.querySelector('[data-tour-mask-bottom]'),
    };
    const ring = root.querySelector('[data-tour-ring]');
    const badge = root.querySelector('[data-tour-badge]');
    const popover = root.querySelector('[data-tour-popover]');
    const arrow = root.querySelector('[data-tour-arrow]');
    const chip = root.querySelector('[data-tour-chip]');

    const isHelpMode = config.mode === 'help';

    const els = {
        stepBadge: root.querySelector('[data-tour-step-badge]'),
        stepLabel: root.querySelector('[data-tour-step-label]'),
        title: root.querySelector('[data-tour-title]'),
        body: root.querySelector('[data-tour-body]'),
        cta: root.querySelector('[data-tour-cta]'),
        wait: root.querySelector('[data-tour-wait]'),
        actions: root.querySelector('[data-tour-actions]'),
        dots: root.querySelector('[data-tour-dots]'),
        prev: root.querySelector('[data-tour-prev]'),
        next: root.querySelector('[data-tour-next]'),
        nav: root.querySelector('[data-tour-nav]'),
        close: root.querySelector('[data-tour-close]'),
        chipBtn: root.querySelector('[data-tour-chip-open]'),
        chipText: root.querySelector('[data-tour-chip-text]'),
    };

    const post = (url, fields = {}) => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.style.display = 'none';

        const token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = csrf;
        form.appendChild(token);

        Object.entries(fields).forEach(([name, value]) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    };

    const clearActiveTarget = () => {
        if (activeTarget) {
            activeTarget.classList.remove('onboarding-tour-focus', 'onboarding-tour-clickable');
            activeTarget = null;
        }

        hiddenTitleElements.forEach((el) => {
            if (el.dataset.tourOriginalTitle !== undefined) {
                el.setAttribute('title', el.dataset.tourOriginalTitle);
                delete el.dataset.tourOriginalTitle;
            }
        });
        hiddenTitleElements = [];
    };

    const hideNativeTooltips = (el) => {
        hiddenTitleElements.forEach((node) => {
            if (node.dataset.tourOriginalTitle !== undefined) {
                node.setAttribute('title', node.dataset.tourOriginalTitle);
                delete node.dataset.tourOriginalTitle;
            }
        });

        hiddenTitleElements = [...el.querySelectorAll('[title]')];
        if (el.hasAttribute('title')) {
            hiddenTitleElements.unshift(el);
        }

        hiddenTitleElements.forEach((node) => {
            node.dataset.tourOriginalTitle = node.getAttribute('title') || '';
            node.removeAttribute('title');
        });
    };

    const shouldScrollToTarget = (target) => {
        if (!target) {
            return false;
        }

        const rect = target.getBoundingClientRect();
        const viewportPadding = 96;

        return rect.top < viewportPadding
            || rect.bottom > window.innerHeight - viewportPadding
            || rect.left < 16
            || rect.right > window.innerWidth - 16;
    };

    const isTargetVisible = (target) => {
        if (!target) {
            return false;
        }

        if (target.closest('.hidden')) {
            return false;
        }

        const rect = target.getBoundingClientRect();

        return rect.width > 0 && rect.height > 0;
    };

    const blockTourScroll = (event) => {
        if (!open) {
            return;
        }

        const slide = slides[index];
        if (slide?.allowScroll) {
            return;
        }

        if (event.target?.closest?.('[data-tour-popover]')) {
            return;
        }

        event.preventDefault();
    };

    const blockTourScrollKeys = (event) => {
        if (!open) {
            return;
        }

        const slide = slides[index];
        if (slide?.allowScroll) {
            return;
        }

        if (event.target?.closest?.('input, textarea, select, [contenteditable="true"]')) {
            return;
        }

        if ([' ', 'PageUp', 'PageDown', 'End', 'Home', 'ArrowUp', 'ArrowDown'].includes(event.key)) {
            event.preventDefault();
        }
    };

    const lockScroll = () => {
        window.addEventListener('wheel', blockTourScroll, { passive: false });
        window.addEventListener('touchmove', blockTourScroll, { passive: false });
        window.addEventListener('keydown', blockTourScrollKeys, true);
    };

    const unlockScroll = () => {
        window.removeEventListener('wheel', blockTourScroll);
        window.removeEventListener('touchmove', blockTourScroll);
        window.removeEventListener('keydown', blockTourScrollKeys, true);
    };

    const applyScrollLock = () => {
        unlockScroll();

        if (!open || isHelpMode) {
            return;
        }

        const slide = slides[index];
        if (!slide?.allowScroll) {
            lockScroll();
        }
    };

    const hideTargetUi = () => {
        Object.values(masks).forEach((el) => el?.classList.add('hidden'));
        ring?.classList.add('hidden');
        badge?.classList.add('hidden');
        arrow?.classList.add('hidden');
        backdrop?.classList.remove('hidden');
        clearActiveTarget();
    };

    const padRect = (rect, pad = 12) => ({
        top: rect.top - pad,
        left: rect.left - pad,
        width: rect.width + pad * 2,
        height: rect.height + pad * 2,
        bottom: rect.bottom + pad,
        right: rect.right + pad,
    });

    const visibleRect = (rect) => {
        const margin = 12;
        const top = Math.min(Math.max(rect.top, margin), window.innerHeight - margin);
        const left = Math.min(Math.max(rect.left, margin), window.innerWidth - margin);
        const right = Math.max(left, Math.min(rect.right, window.innerWidth - margin));
        const bottom = Math.max(top, Math.min(rect.bottom, window.innerHeight - margin));

        return {
            top,
            left,
            right,
            bottom,
            width: right - left,
            height: bottom - top,
        };
    };

    const intersectWithViewport = (rect) => {
        const vw = window.innerWidth;
        const vh = window.innerHeight;
        const top = Math.max(0, rect.top);
        const left = Math.max(0, rect.left);
        const right = Math.min(vw, rect.right);
        const bottom = Math.min(vh, rect.bottom);

        return {
            top,
            left,
            right,
            bottom,
            width: Math.max(0, right - left),
            height: Math.max(0, bottom - top),
        };
    };

    const highlightRectFor = (rect, slide) => {
        if (slide.highlightFullTarget) {
            const margin = 12;
            const width = Math.min(rect.width, window.innerWidth - margin * 2);
            const left = Math.max(margin, Math.min(rect.left, window.innerWidth - width - margin));

            return {
                top: rect.top,
                left,
                width,
                height: rect.height,
                right: left + width,
                bottom: rect.top + rect.height,
            };
        }

        return displayRectFor(rect, slide);
    };

    const displayRectFor = (rect, slide) => {
        const margin = 12;
        const maxWidth = slide.maxHighlightWidth ?? Math.min(window.innerWidth - 48, 960);
        const maxHeight = slide.maxHighlightHeight ?? (
            slide.highlightFullTarget
                ? window.innerHeight - margin * 2
                : Math.min(window.innerHeight - 96, 520)
        );
        const clipped = visibleRect(rect);

        if (clipped.width <= maxWidth && clipped.height <= maxHeight) {
            return clipped;
        }

        const width = Math.min(clipped.width, maxWidth);
        const height = Math.min(clipped.height, maxHeight);
        const left = slide.highlightAnchor === 'top'
            ? clipped.left
            : Math.min(
                Math.max(rect.left + rect.width / 2 - width / 2, clipped.left),
                clipped.right - width
            );
        const top = slide.highlightAnchor === 'top'
            ? clipped.top
            : Math.min(
                Math.max(rect.top + rect.height / 2 - height / 2, clipped.top),
                clipped.bottom - height
            );

        return {
            top,
            left,
            width,
            height,
            right: left + width,
            bottom: top + height,
        };
    };

    const updateMask = (box) => {
        const vw = window.innerWidth;
        const vh = window.innerHeight;
        const hole = intersectWithViewport(box);

        backdrop?.classList.add('hidden');

        if (hole.width <= 0 || hole.height <= 0) {
            Object.values(masks).forEach((el) => el?.classList.add('hidden'));
            backdrop?.classList.remove('hidden');
            return;
        }

        if (masks.top) {
            masks.top.classList.remove('hidden');
            masks.top.style.top = '0';
            masks.top.style.left = '0';
            masks.top.style.width = `${vw}px`;
            masks.top.style.height = `${Math.max(0, hole.top)}px`;
        }
        if (masks.left) {
            masks.left.classList.remove('hidden');
            masks.left.style.top = `${hole.top}px`;
            masks.left.style.left = '0';
            masks.left.style.width = `${Math.max(0, hole.left)}px`;
            masks.left.style.height = `${hole.height}px`;
        }
        if (masks.right) {
            masks.right.classList.remove('hidden');
            masks.right.style.top = `${hole.top}px`;
            masks.right.style.left = `${hole.left + hole.width}px`;
            masks.right.style.width = `${Math.max(0, vw - hole.left - hole.width)}px`;
            masks.right.style.height = `${hole.height}px`;
        }
        if (masks.bottom) {
            masks.bottom.classList.remove('hidden');
            masks.bottom.style.top = `${hole.top + hole.height}px`;
            masks.bottom.style.left = '0';
            masks.bottom.style.width = `${vw}px`;
            masks.bottom.style.height = `${Math.max(0, vh - hole.top - hole.height)}px`;
        }
    };

    const positionRing = (box) => {
        if (!ring) {
            return;
        }

        const ringBox = intersectWithViewport(box);

        if (ringBox.width <= 0 || ringBox.height <= 0) {
            ring.classList.add('hidden');
            return;
        }

        ring.classList.remove('hidden');
        ring.style.top = `${ringBox.top}px`;
        ring.style.left = `${ringBox.left}px`;
        ring.style.width = `${Math.max(0, ringBox.width)}px`;
        ring.style.height = `${Math.max(0, ringBox.height)}px`;
    };

    const positionBadge = (box, slide) => {
        if (!badge || slide.clickTarget === false) {
            badge?.classList.add('hidden');
            return;
        }

        badge.classList.remove('hidden');
        const badgeRect = badge.getBoundingClientRect();
        const badgeW = badgeRect.width || 110;
        const badgeH = badgeRect.height || 36;

        let top = box.top - badgeH - 10;
        let left = box.left + box.width / 2 - badgeW / 2;

        if (top < 12) {
            top = box.bottom + 10;
            badge.dataset.placement = 'below';
        } else {
            badge.dataset.placement = 'above';
        }

        left = Math.min(Math.max(12, left), window.innerWidth - badgeW - 12);
        top = Math.min(Math.max(12, top), window.innerHeight - badgeH - 12);

        badge.style.top = `${top}px`;
        badge.style.left = `${left}px`;
    };

    const showTargetUi = (el, slide) => {
        clearActiveTarget();
        activeTarget = el;
        el.classList.add('onboarding-tour-focus');
        hideNativeTooltips(el);

        if (slide.clickTarget !== false) {
            el.classList.add('onboarding-tour-clickable');
        }

        const rect = highlightRectFor(el.getBoundingClientRect(), slide);
        const box = padRect(rect, slide.highlightPad ?? 10);

        updateMask(box);
        positionRing(box);
        positionBadge(intersectWithViewport(box), slide);

        return intersectWithViewport(box);
    };

    const resetArrow = () => {
        arrow?.classList.remove('tour-arrow-top', 'tour-arrow-bottom', 'tour-arrow-left', 'tour-arrow-right');
    };

    const positionPopover = (rect, placement) => {
        if (!popover) {
            return;
        }

        popover.style.visibility = 'hidden';
        popover.classList.remove('hidden');

        const margin = 16;
        const gap = 22;
        const popRect = popover.getBoundingClientRect();
        let top;
        let left;
        let effectivePlacement = placement;

        resetArrow();

        if (!rect || placement === 'center') {
            top = Math.max(margin, (window.innerHeight - popRect.height) / 2);
            left = Math.max(margin, (window.innerWidth - popRect.width) / 2);
            arrow?.classList.add('hidden');
        } else if (placement === 'left' || placement === 'right') {
            const sideLeft = rect.left - popRect.width - gap;
            const sideRight = rect.right + gap;
            const fitsLeft = sideLeft >= margin;
            const fitsRight = sideRight + popRect.width <= window.innerWidth - margin;

            if (placement === 'left' && !fitsLeft && fitsRight) {
                effectivePlacement = 'right';
            } else if (placement === 'right' && !fitsRight && fitsLeft) {
                effectivePlacement = 'left';
            } else if (placement === 'left' && !fitsLeft) {
                effectivePlacement = 'bottom';
            } else if (placement === 'right' && !fitsRight) {
                effectivePlacement = 'bottom';
            }

            if (effectivePlacement === 'left' || effectivePlacement === 'right') {
                left = effectivePlacement === 'left' ? sideLeft : sideRight;
                top = rect.top + rect.height / 2 - popRect.height / 2;
                left = Math.min(Math.max(margin, left), window.innerWidth - popRect.width - margin);
                top = Math.min(Math.max(margin, top), window.innerHeight - popRect.height - margin);

                if (rect.bottom > window.innerHeight - popRect.height / 2) {
                    top = Math.max(margin, rect.top - popRect.height - gap);
                } else if (rect.top < popRect.height / 2) {
                    top = Math.min(window.innerHeight - popRect.height - margin, rect.bottom + gap);
                }

                if (arrow) {
                    arrow.classList.remove('hidden');
                    arrow.classList.add(effectivePlacement === 'left' ? 'tour-arrow-right' : 'tour-arrow-left');
                    const targetCenterY = rect.top + rect.height / 2;
                    const arrowTop = Math.min(
                        popRect.height - 24,
                        Math.max(16, targetCenterY - top - 8)
                    );
                    arrow.style.top = `${arrowTop}px`;
                    arrow.style.bottom = 'auto';
                    arrow.style.left = effectivePlacement === 'left' ? 'auto' : '-7px';
                    arrow.style.right = effectivePlacement === 'left' ? '-7px' : 'auto';
                }
            } else {
                top = rect.bottom + gap;
                left = rect.left + rect.width / 2 - popRect.width / 2;
                left = Math.min(Math.max(margin, left), window.innerWidth - popRect.width - margin);
                top = Math.min(Math.max(margin, top), window.innerHeight - popRect.height - margin);

                if (arrow) {
                    arrow.classList.remove('hidden');
                    arrow.classList.add('tour-arrow-top');
                    const arrowLeft = Math.min(
                        popRect.width - 24,
                        Math.max(16, rect.left + rect.width / 2 - left - 8)
                    );
                    arrow.style.left = `${arrowLeft}px`;
                    arrow.style.right = 'auto';
                    arrow.style.top = '-7px';
                    arrow.style.bottom = 'auto';
                }
            }
        } else {
            const preferTop = placement === 'top' || (placement === 'auto' && rect.top > window.innerHeight * 0.5);

            if (preferTop) {
                top = rect.top - popRect.height - gap;
                effectivePlacement = 'bottom';
            } else {
                top = rect.bottom + gap;
                effectivePlacement = 'top';
            }

            left = rect.left + rect.width / 2 - popRect.width / 2;
            left = Math.min(Math.max(margin, left), window.innerWidth - popRect.width - margin);
            top = Math.min(Math.max(margin, top), window.innerHeight - popRect.height - margin);

            if (arrow) {
                arrow.classList.remove('hidden');
                arrow.classList.add(effectivePlacement === 'top' ? 'tour-arrow-top' : 'tour-arrow-bottom');
                const arrowLeft = Math.min(
                    popRect.width - 24,
                    Math.max(16, rect.left + rect.width / 2 - left - 8)
                );
                arrow.style.left = `${arrowLeft}px`;
                arrow.style.right = 'auto';
                arrow.style.top = effectivePlacement === 'top' ? '-7px' : 'auto';
                arrow.style.bottom = effectivePlacement === 'top' ? 'auto' : '-7px';
            }
        }

        popover.style.top = `${top}px`;
        popover.style.left = `${left}px`;
        popover.style.visibility = 'visible';
    };

    const renderDots = () => {
        if (!els.dots) {
            return;
        }
        if (slides.length <= 1) {
            els.dots.innerHTML = '';
            return;
        }
        els.dots.innerHTML = slides
            .map((_, i) => `<span class="h-1.5 rounded-full transition-all ${i === index ? 'w-6 bg-blue-600' : 'w-1.5 bg-slate-300'}"></span>`)
            .join('');
    };

    const renderActions = (slide) => {
        if (!els.actions) {
            return;
        }
        els.actions.innerHTML = '';

        if (slide.clickTarget && slide.target) {
            return;
        }

        if (slide.action === 'start') {
            els.actions.innerHTML = `<button type="button" data-tour-action="start" class="inline-flex min-h-[2.75rem] w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-blue-700">Start met instellen</button>`;
            return;
        }

        if (slide.action === 'continue_users') {
            els.actions.innerHTML = `<button type="button" data-tour-action="continue_users" class="inline-flex min-h-[2.75rem] w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-blue-700">Doorgaan naar takenlijst</button>`;
            return;
        }

        if (slide.action === 'users_more_choice') {
            const createUrl = config.routes?.users_create || '/admin/users/create';
            els.actions.innerHTML = `
                <a href="${createUrl}" class="inline-flex min-h-[2.75rem] w-full items-center justify-center gap-2 rounded-xl border-2 border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Nog een gebruiker toevoegen
                </a>
                <button type="button" data-tour-action="continue_users" class="inline-flex min-h-[2.75rem] w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-blue-700">
                    Doorgaan met onboarding
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </button>
            `;
            return;
        }

        if (slide.action === 'list_choice') {
            els.actions.innerHTML = `
                <button type="button" data-tour-choice="template" class="inline-flex min-h-[2.75rem] flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-blue-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    Template gebruiken
                </button>
                <button type="button" data-tour-choice="custom" class="inline-flex min-h-[2.75rem] flex-1 items-center justify-center gap-2 rounded-xl border-2 border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Eigen lijst maken
                </button>
            `;
            return;
        }

        if (slide.showCustomListOption) {
            els.actions.innerHTML = `
                <button type="button" data-tour-choice="custom" class="inline-flex min-h-[2.75rem] w-full items-center justify-center gap-2 rounded-xl border-2 border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Toch een eigen lijst maken
                </button>
            `;
        }
    };

    const focusFirstField = (el) => {
        const field = el.querySelector('input:not([type="hidden"]):not([type="file"]), textarea, select');
        if (field && typeof field.focus === 'function') {
            window.setTimeout(() => field.focus({ preventScroll: true }), 120);
        }
    };

    const paintTarget = (slide, target, { focusField = false } = {}) => {
        if (target) {
            const rect = showTargetUi(target, slide);
            positionPopover(rect, slide.placement || 'auto');
            if (focusField && slide.clickTarget === false) {
                focusFirstField(target);
            }
            return;
        }

        hideTargetUi();
        positionPopover(null, 'center');
    };

    const repaintPositions = () => {
        if (!open) {
            return;
        }

        const slide = slides[index];
        if (!slide) {
            return;
        }

        const target = slide.target ? document.querySelector(slide.target) : null;
        if (slide.target && !target) {
            return;
        }

        paintTarget(slide, target, { focusField: false });
    };

    const renderSlide = ({ scrollToTarget = true } = {}) => {
        clearTimeout(waitTimer);

        const slide = slides[index];
        if (!slide) {
            return;
        }

        const globalStep = config.step_number || 1;
        const needsClick = Boolean(slide.target && slide.clickTarget !== false);

        if (els.stepBadge) {
            els.stepBadge.textContent = String(globalStep);
        }
        if (els.stepLabel) {
            if (isHelpMode) {
                els.stepLabel.textContent = `Hulp · tip ${index + 1}/${slides.length}`;
            } else {
                els.stepLabel.textContent = `Stap ${globalStep} van ${config.total_steps || 5} · tip ${index + 1}/${slides.length}`;
            }
        }
        if (els.chipText) {
            els.chipText.textContent = isHelpMode
                ? (config.fab_label || 'Heb je hulp nodig?')
                : `Hulp · stap ${globalStep}/${config.total_steps || 5}`;
        }
        if (els.title) {
            els.title.textContent = slide.title || '';
        }
        if (els.body) {
            els.body.textContent = slide.body || '';
        }
        if (els.cta) {
            const ctaText = slide.cta
                || (needsClick ? 'Klik op het gemarkeerde element op de pagina.' : 'Vul dit in en ga daarna naar de volgende tip.');
            if (slide.target || slide.cta) {
                els.cta.textContent = ctaText;
                els.cta.classList.remove('hidden');
            } else {
                els.cta.classList.add('hidden');
            }
        }
        if (els.wait) {
            els.wait.classList.add('hidden');
        }

        renderActions(slide);
        renderDots();

        if (els.prev) {
            els.prev.disabled = index === 0;
            els.prev.classList.toggle('opacity-30', index === 0);
        }
        if (els.next) {
            const isLast = index >= slides.length - 1;
            const hasAction = Boolean(slide.action);
            const nextDisabled = needsClick || hasAction;
            els.next.disabled = nextDisabled;
            els.next.classList.toggle('opacity-40', nextDisabled);
            els.next.classList.toggle('cursor-not-allowed', nextDisabled);
            els.next.textContent = needsClick
                ? '↑ Klik op de pagina'
                : (hasAction ? 'Kies een optie ↑' : (isLast ? 'Klaar' : 'Volgende tip →'));
        }

        const target = slide.target ? document.querySelector(slide.target) : null;

        if (slide.target && (!target || (slide.waitForTarget && !isTargetVisible(target)))) {
            hideTargetUi();
            positionPopover(null, 'center');
            if (els.wait && slide.waitForTarget) {
                els.wait.textContent = target && !isTargetVisible(target)
                    ? 'Klik op het gemarkeerde element op de pagina om verder te gaan…'
                    : 'Even wachten tot de pagina geladen is…';
                els.wait.classList.remove('hidden');
            }
            if (slide.waitForTarget) {
                waitTimer = setTimeout(renderSlide, 350);
            }
            return;
        }

        const paint = () => paintTarget(slide, target, { focusField: scrollToTarget });

        if (target && scrollToTarget && (shouldScrollToTarget(target) || slide.scrollBlock)) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: slide.scrollBlock || 'nearest',
                inline: 'nearest',
            });
            window.setTimeout(paint, 450);
        } else {
            paint();
        }

        requestAnimationFrame(paint);
        applyScrollLock();
    };

    const scheduleRepaint = () => {
        clearTimeout(repaintTimer);
        repaintTimer = window.setTimeout(repaintPositions, 80);
    };

    const setOpen = (value) => {
        open = value;
        overlay?.classList.toggle('hidden', !open);

        if (isHelpMode) {
            chip?.classList.remove('hidden');
        } else {
            chip?.classList.toggle('hidden', open);
        }

        if (open) {
            applyScrollLock();
            renderSlide();
        } else {
            unlockScroll();
            hideTargetUi();
        }
    };

    const landHelpFab = () => {
        if (!chip || !isHelpMode) {
            return;
        }

        chip.classList.remove('admin-help-fab-wait', 'hidden');
        chip.classList.add('admin-help-fab-land');
    };

    const goNext = () => {
        const slide = slides[index];
        if (slide?.clickTarget && slide?.target) {
            return;
        }
        if (slide?.action) {
            return;
        }
        if (index < slides.length - 1) {
            index += 1;
            renderSlide();
            return;
        }
        setOpen(false);
    };

    root.addEventListener('click', (event) => {
        const action = event.target.closest('[data-tour-action]')?.dataset.tourAction;
        if (action === 'start') {
            post(config.routes?.start || '/admin/onboarding/start');
            return;
        }
        if (action === 'continue_users') {
            post(config.routes?.continue_users || '/admin/onboarding/users/continue');
            return;
        }
        if (action === 'skip') {
            if (window.confirm('Onboarding overslaan? Je kunt later alles zelf instellen via het menu.')) {
                post(config.routes?.skip || '/admin/onboarding/skip');
            }
            return;
        }

        const choice = event.target.closest('[data-tour-choice]')?.dataset.tourChoice;
        if (choice) {
            post(config.routes?.list_choice || '/admin/onboarding/list-choice', { choice });
        }
    });

    document.addEventListener('click', (event) => {
        if (!open) {
            return;
        }

        const slide = slides[index];

        if (slide?.dismissOnSelector && event.target.closest(slide.dismissOnSelector)) {
            setOpen(false);
            return;
        }

        if (!slide?.target || slide.clickTarget === false) {
            return;
        }

        const target = document.querySelector(slide.target);
        if (!target || !target.contains(event.target)) {
            return;
        }

        if (index < slides.length - 1) {
            index += 1;
            window.setTimeout(renderSlide, 350);
        } else {
            setOpen(false);
        }
    }, true);

    els.prev?.addEventListener('click', () => {
        if (index > 0) {
            index -= 1;
            renderSlide();
        }
    });

    els.next?.addEventListener('click', goNext);
    els.close?.addEventListener('click', () => setOpen(false));
    els.chipBtn?.addEventListener('click', () => setOpen(true));

    window.addEventListener('resize', scheduleRepaint);
    window.addEventListener('scroll', scheduleRepaint, true);
    document.addEventListener('onboarding:targets-updated', scheduleRepaint);
    document.addEventListener('onboarding:modal-opened', scheduleRepaint);
    document.addEventListener('admin-help:land-fab', landHelpFab);

    if (isHelpMode) {
        if (root.dataset.helpAnimate !== '1') {
            chip?.classList.remove('admin-help-fab-wait', 'hidden');
        }
        setOpen(false);
    } else {
        setOpen(true);
    }
}
