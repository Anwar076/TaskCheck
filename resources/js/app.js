import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Task list drag-and-drop (only on lists show page)
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('[data-sortable-reorder-url]')) {
        import('./list-sortable.js').then(({ initListSortable }) => initListSortable());
    }
});

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
