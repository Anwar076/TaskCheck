<div data-ios-push-prompt class="hidden mx-4 mb-4 xl:mx-auto xl:max-w-7xl rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="font-semibold" data-ios-push-title>Meldingen op je telefoon</p>
            <p class="mt-1 leading-5" data-ios-push-body></p>
        </div>
        <button type="button" data-ios-push-dismiss class="shrink-0 rounded-lg p-1 text-blue-700 hover:bg-blue-100" aria-label="Melding sluiten">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <button type="button" data-ios-push-enable class="mt-3 hidden rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">
        Meldingen aanzetten
    </button>
</div>
<script>
(function () {
    const STORAGE_KEY = 'taskcheck:push-prompt-dismissed';

    function isIosDevice() {
        const ua = window.navigator.userAgent || '';
        return /iPad|iPhone|iPod/.test(ua)
            || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
    }

    function isStandalonePwa() {
        return window.matchMedia('(display-mode: standalone)').matches
            || window.matchMedia('(display-mode: fullscreen)').matches
            || window.navigator.standalone === true;
    }

    function isNativeApp() {
        return document.documentElement.classList.contains('is-native-app');
    }

    function wasDismissed() {
        try {
            return window.localStorage.getItem(STORAGE_KEY) === '1';
        } catch (error) {
            return false;
        }
    }

    function markDismissed() {
        try {
            window.localStorage.setItem(STORAGE_KEY, '1');
        } catch (error) {
            // ignore
        }
    }

    async function requestPushPermission() {
        if (typeof window.requestTaskcheckPushPermission === 'function') {
            return window.requestTaskcheckPushPermission();
        }

        if (!('Notification' in window)) {
            return 'unsupported';
        }

        const permission = await Notification.requestPermission();
        if (permission === 'granted' && typeof window.subscribeForBackgroundPush === 'function') {
            await window.subscribeForBackgroundPush();
        }

        return permission;
    }

    function initIosPushPrompt() {
        const root = document.querySelector('[data-ios-push-prompt]');
        if (!root) {
            return;
        }

        const title = root.querySelector('[data-ios-push-title]');
        const body = root.querySelector('[data-ios-push-body]');
        const enableButton = root.querySelector('[data-ios-push-enable]');
        const dismissButton = root.querySelector('[data-ios-push-dismiss]');
        const ios = isIosDevice();
        const standalone = isStandalonePwa();
        const nativeApp = isNativeApp();
        const notificationSupported = 'Notification' in window;
        const permission = notificationSupported ? Notification.permission : 'unsupported';

        if (permission === 'granted' && !nativeApp) {
            root.classList.add('hidden');
            return;
        }

        if (nativeApp && window.TaskCheckNative) {
            window.TaskCheckNative.plugin('PushNotifications')?.checkPermissions?.().then((result) => {
                if (result?.receive === 'granted') {
                    root.classList.add('hidden');
                }
            }).catch(() => {});
        }

        if (wasDismissed() && permission !== 'denied') {
            root.classList.add('hidden');
            return;
        }

        enableButton.classList.add('hidden');
        title.textContent = 'Meldingen op je telefoon';

        if (nativeApp) {
            body.textContent = 'Zet meldingen aan om een seintje te krijgen bij nieuwe taken of controles, ook als de app dicht is.';
            enableButton.classList.remove('hidden');
        } else if (ios && !standalone) {
            body.textContent = 'Op iPhone krijg je meldingen alleen als TaskCheck op je beginscherm staat. Tik op Deel (vierkant met pijl) → Zet op beginscherm. Open de app daarna vanaf je beginscherm.';
        } else if (!notificationSupported) {
            body.textContent = 'Deze browser ondersteunt geen lockscreen-meldingen.';
        } else if (permission === 'denied') {
            title.textContent = 'Meldingen zijn geblokkeerd';
            body.textContent = ios
                ? 'Ga op je iPhone naar Instellingen → TaskCheck → Meldingen en zet meldingen aan. Open daarna de app opnieuw vanaf je beginscherm.'
                : 'Meldingen zijn geblokkeerd in je browserinstellingen voor deze website. Zet ze daar aan en vernieuw de pagina.';
        } else {
            body.textContent = 'Zet meldingen aan om een seintje te krijgen bij nieuwe taken of controles, ook als de app dicht is.';
            enableButton.classList.remove('hidden');
        }

        root.classList.remove('hidden');

        enableButton.addEventListener('click', async function () {
            enableButton.disabled = true;
            enableButton.textContent = 'Bezig…';
            try {
                const result = await requestPushPermission();
                if (result === 'granted') {
                    markDismissed();
                    root.classList.add('hidden');
                    return;
                }
                if (result === 'denied') {
                    title.textContent = 'Meldingen zijn geblokkeerd';
                    body.textContent = ios
                        ? 'Ga op je iPhone naar Instellingen → TaskCheck → Meldingen en zet meldingen aan.'
                        : 'Meldingen zijn geblokkeerd in je browserinstellingen voor deze website.';
                    enableButton.classList.add('hidden');
                    return;
                }
            } catch (error) {
                body.textContent = 'Meldingen aanzetten is niet gelukt. Probeer het opnieuw vanaf je beginscherm.';
            }
            enableButton.disabled = false;
            enableButton.textContent = 'Meldingen aanzetten';
        });

        dismissButton?.addEventListener('click', function () {
            markDismissed();
            root.classList.add('hidden');
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initIosPushPrompt);
    } else {
        initIosPushPrompt();
    }
})();
</script>
