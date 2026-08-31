import './bootstrap';
import './capacitor-native.js';

import { initCalendarSlotPicker } from './calendar-slot-picker.js';
import { initTaskCreateModal } from './task-create-modal.js';
import { initListOrder } from './list-order.js';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const markNativeShell = () => {
    const native = !!(window.Capacitor && (
        typeof window.Capacitor.isNativePlatform === 'function'
            ? window.Capacitor.isNativePlatform()
            : window.Capacitor.isNative
    ));
    if (native || (/Capacitor|wv/i.test(navigator.userAgent) && /Android/i.test(navigator.userAgent))) {
        document.documentElement.classList.add('is-native-app');
    }
};
markNativeShell();

// Page-specific controls. Run immediately as well when this bundle is loaded
// after DOMContentLoaded (for example from browser or service-worker cache).
const initPageControls = () => {
    if (document.querySelector('[data-sortable-reorder-url]')) {
        import('./list-sortable.js').then(({ initListSortable }) => initListSortable());
    }

    if (document.querySelector('[data-list-order-url]')) {
        initListOrder();
    }

    if (document.querySelector('[data-calendar-slot-grid]')) {
        initCalendarSlotPicker();
    }

    if (document.querySelector('[data-task-create-modal]')) {
        initTaskCreateModal();
    }

    const tourRoot = document.getElementById('onboarding-tour-root') || document.getElementById('admin-help-tour-root');
    if (tourRoot) {
        import('./onboarding-tour.js').then(({ initOnboardingTour }) => initOnboardingTour(tourRoot));
    }
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPageControls, { once: true });
} else {
    initPageControls();
}

// PWA update manager: always auto-updates to latest version.
(() => {
    if (!('serviceWorker' in navigator)) {
        return;
    }

    let refreshTriggered = false;

    function applyUpdate(worker) {
        if (!worker) return;
        worker.postMessage({ type: 'SKIP_WAITING' });
    }

    function watchInstallingWorker(worker) {
        if (!worker) {
            return;
        }

        worker.addEventListener('statechange', () => {
            if (worker.state === 'installed' && navigator.serviceWorker.controller) {
                applyUpdate(worker);
            }
        });
    }

    function attachRegistrationListeners(registration) {
        if (!registration) {
            return;
        }

        if (registration.waiting && navigator.serviceWorker.controller) {
            applyUpdate(registration.waiting);
        }

        registration.addEventListener('updatefound', () => {
            watchInstallingWorker(registration.installing);
        });
    }

    navigator.serviceWorker.addEventListener('controllerchange', () => {
        if (refreshTriggered) {
            return;
        }
        refreshTriggered = true;
        window.location.reload();
    });

    window.addEventListener('load', async () => {
        if (document.documentElement.classList.contains('is-native-app')) {
            return;
        }
        try {
            const registration = await navigator.serviceWorker.register('/sw.js');
            attachRegistrationListeners(registration);
            await registration.update().catch(() => {});
            attachRegistrationListeners(registration);

            // Periodic checks ensure users see updates without manual reload.
            setInterval(() => {
                registration.update().catch(() => {});
            }, 60 * 1000);

            const forceUpdateCheck = () => {
                registration.update().catch(() => {});
                if (registration.waiting && navigator.serviceWorker.controller) {
                    applyUpdate(registration.waiting);
                }
            };

            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'visible') {
                    forceUpdateCheck();
                }
            });

            window.addEventListener('focus', forceUpdateCheck);
        } catch (error) {
            console.warn('Service worker registration failed', error);
        }
    });
})();
