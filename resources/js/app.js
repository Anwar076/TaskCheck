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

    function buildUpdateToast(onUpdateClick) {
        const existing = document.querySelector('[data-pwa-update-toast]');
        if (existing) {
            return;
        }

        const toast = document.createElement('div');
        toast.setAttribute('data-pwa-update-toast', '1');
        toast.className = 'fixed bottom-4 left-4 right-4 sm:left-auto sm:right-6 sm:max-w-sm z-[9999] rounded-2xl border border-blue-200 bg-white shadow-2xl p-4';
        toast.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="mt-0.5 inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700">!</div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-slate-900">Er is een update beschikbaar</p>
                    <p class="mt-1 text-xs text-slate-600">Klik op updaten om de nieuwste versie direct te laden.</p>
                    <div class="mt-3 flex items-center gap-2">
                        <button type="button" data-update-now class="inline-flex items-center justify-center rounded-lg bg-blue-600 text-white text-xs font-semibold px-3 py-2 hover:bg-blue-700 transition-colors">Updaten</button>
                        <button type="button" data-update-later class="inline-flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 text-xs font-semibold px-3 py-2 hover:bg-slate-50 transition-colors">Later</button>
                    </div>
                </div>
            </div>
        `;

        const updateNowButton = toast.querySelector('[data-update-now]');
        const updateLaterButton = toast.querySelector('[data-update-later]');

        updateNowButton?.addEventListener('click', () => {
            onUpdateClick();
        });

        updateLaterButton?.addEventListener('click', () => {
            toast.remove();
        });

        document.body.appendChild(toast);
    }

    function watchInstallingWorker(worker) {
        if (!worker) {
            return;
        }

        worker.addEventListener('statechange', () => {
            if (worker.state === 'installed' && navigator.serviceWorker.controller) {
                buildUpdateToast(() => {
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
            buildUpdateToast(() => {
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

            // Periodic checks ensure users see updates without manual reload.
            setInterval(() => {
                registration.update().catch(() => {});
            }, 60 * 1000);
        } catch (error) {
            console.warn('Service worker registration failed', error);
        }
    });
})();
