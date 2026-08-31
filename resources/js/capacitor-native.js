const capacitor = () => window.Capacitor;
const plugin = (name) => capacitor()?.Plugins?.[name] || null;
const nativePlatform = () => {
    if (!capacitor()?.isNativePlatform?.()) {
        return null;
    }

    return capacitor()?.getPlatform?.() || null;
};
const isNativeApp = () => ['ios', 'android'].includes(nativePlatform());
const isLoggedIn = () => document.querySelector('meta[name="taskcheck-auth"]')?.content === '1';

async function postNativeToken(token) {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const platform = nativePlatform();
    if (!csrf || !token || !platform || !isLoggedIn()) {
        return;
    }

    const response = await fetch('/push/native/subscribe', {
        method: 'POST',
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({
            token,
            platform,
            device_name: navigator.userAgent,
        }),
    });

    if (!response.ok) {
        throw new Error(`Registratie van pushtoken mislukte (${response.status}).`);
    }
}

async function showLocalNotification(notification) {
    const localNotifications = plugin('LocalNotifications');
    if (!localNotifications) {
        return;
    }

    const permissions = await localNotifications.checkPermissions();
    const result = permissions.display === 'prompt'
        ? await localNotifications.requestPermissions()
        : permissions;
    if (result.display !== 'granted') {
        return;
    }

    await localNotifications.schedule({
        notifications: [{
            id: Number(notification.id) || Math.floor(Date.now() / 1000),
            title: notification.title || notification.notification?.title || 'TaskCheck',
            body: notification.body || notification.notification?.body || notification.message || 'Nieuwe melding',
            extra: notification.data || {},
            schedule: { at: new Date(Date.now() + 250) },
        }],
    });
}

function hidePushPrompt() {
    document.querySelector('[data-ios-push-prompt]')?.classList.add('hidden');
}

async function requestPushPermission() {
    const push = plugin('PushNotifications');
    if (!push) {
        return 'unsupported';
    }

    let permissions = await push.checkPermissions();
    if (permissions.receive === 'prompt' || permissions.receive === 'prompt-with-rationale') {
        permissions = await push.requestPermissions();
    }

    if (permissions.receive === 'granted') {
        await push.register();
        hidePushPrompt();
        return 'granted';
    }

    return permissions.receive === 'denied' ? 'denied' : 'default';
}

async function initializePushNotifications() {
    const push = plugin('PushNotifications');
    if (!push || !isLoggedIn()) {
        return;
    }

    if (!window.__taskcheckNativePushBound) {
        window.__taskcheckNativePushBound = true;
        await push.addListener('registration', ({ value }) => {
            postNativeToken(value).catch((error) => {
                console.warn('Native pushtoken opslaan mislukt', error);
            });
        });
        await push.addListener('registrationError', (error) => {
            console.warn('Native pushregistratie mislukt', error);
        });
        await push.addListener('pushNotificationReceived', (notification) => {
            if (document.visibilityState === 'visible') {
                showLocalNotification(notification).catch(console.warn);
            }
        });
        await push.addListener('pushNotificationActionPerformed', ({ notification }) => {
            const url = notification?.data?.url;
            if (typeof url === 'string' && url.startsWith('/')) {
                window.location.assign(url);
            }
        });
    }

    const status = await requestPushPermission();
    if (status === 'granted') {
        hidePushPrompt();
    }
}

async function initializeNativeFeatures() {
    if (!isNativeApp()) {
        return;
    }

    window.TaskCheckNative = {
        platform: nativePlatform(),
        plugin,
        showLocalNotification,
        requestPushPermission,
        registerPush: requestPushPermission,
    };
    window.requestTaskcheckPushPermission = requestPushPermission;
    window.subscribeForBackgroundPush = requestPushPermission;

    const statusBar = plugin('StatusBar');
    await statusBar?.setStyle?.({ style: 'DARK' }).catch(() => {});
    await initializePushNotifications().catch((error) => {
        console.warn('Native meldingen konden niet worden gestart', error);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeNativeFeatures, { once: true });
} else {
    initializeNativeFeatures();
}

export { isNativeApp, nativePlatform, plugin, requestPushPermission, showLocalNotification };
