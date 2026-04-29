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

// PWA update manager: shows "update available" prompt and updates app on demand.
(() => {
    if (!('serviceWorker' in navigator)) {
        return;
    }

    let refreshTriggered = false;
    let updateLockActive = false;

    function buildForcedUpdateOverlay(onUpdateClick) {
        const existing = document.querySelector('[data-pwa-update-overlay]');
        if (existing) {
            return;
        }

        const overlay = document.createElement('div');
        overlay.setAttribute('data-pwa-update-overlay', '1');
        overlay.className = 'fixed inset-0 z-[9999] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4';
        overlay.innerHTML = `
            <div class="w-full max-w-md rounded-2xl border border-blue-200 bg-white shadow-2xl p-5 sm:p-6">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold">!</div>
                    <div class="min-w-0 flex-1">
                        <p class="text-base font-semibold text-slate-900">Update vereist</p>
                        <p class="mt-1 text-sm text-slate-600">Er is een nieuwe versie beschikbaar. Update eerst om verder te gaan in de web app.</p>
                    </div>
                </div>
                <div class="mt-5">
                    <button type="button" data-update-now class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 text-white text-sm font-semibold px-4 py-2.5 hover:bg-blue-700 transition-colors">
                        Updaten en doorgaan
                    </button>
                </div>
            </div>
        `;

        const updateNowButton = overlay.querySelector('[data-update-now]');
        updateNowButton?.addEventListener('click', () => {
            updateNowButton.setAttribute('disabled', 'disabled');
            updateNowButton.textContent = 'Bezig met updaten...';
            onUpdateClick();
        });

        document.body.classList.add('overflow-hidden');
        document.body.appendChild(overlay);
    }

    function triggerForcedUpdate(onUpdateClick) {
        updateLockActive = true;
        buildForcedUpdateOverlay(() => {
            onUpdateClick();
            const overlay = document.querySelector('[data-pwa-update-overlay]');
            if (overlay) {
                overlay.innerHTML = `
                    <div class="w-full max-w-md rounded-2xl border border-blue-200 bg-white shadow-2xl p-5 sm:p-6 text-center">
                        <p class="text-base font-semibold text-slate-900">Update wordt toegepast...</p>
                        <p class="mt-2 text-sm text-slate-600">Een ogenblik geduld, de app wordt vernieuwd.</p>
                    </div>
                `;
            }
        });
    }

    function watchInstallingWorker(worker) {
        if (!worker) {
            return;
        }

        worker.addEventListener('statechange', () => {
            if (worker.state === 'installed' && navigator.serviceWorker.controller) {
                triggerForcedUpdate(() => {
                    worker.postMessage({ type: 'SKIP_WAITING' });
                });
            }
        });
    }

    function attachRegistrationListeners(registration) {
        if (!registration) {
            return;
        }

        if (registration.waiting && navigator.serviceWorker.controller) {
            triggerForcedUpdate(() => {
                registration.waiting?.postMessage({ type: 'SKIP_WAITING' });
            });
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
                // If browser already has a waiting worker after check, lock immediately.
                if (!updateLockActive && registration.waiting && navigator.serviceWorker.controller) {
                    triggerForcedUpdate(() => {
                        registration.waiting?.postMessage({ type: 'SKIP_WAITING' });
                    });
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
