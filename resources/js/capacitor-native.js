const capacitor = () => window.Capacitor;
const plugin = (name) => capacitor()?.Plugins?.[name] || null;
const isNativeIos = () => capacitor()?.isNativePlatform?.() && capacitor()?.getPlatform?.() === 'ios';

async function postNativeToken(token) {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrf || !token) return;

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
            platform: 'ios',
            device_name: navigator.userAgent,
        }),
    });

    if (!response.ok) {
        throw new Error(`Registratie van iOS-pushtoken mislukte (${response.status}).`);
    }
}

async function showLocalNotification(notification) {
    const localNotifications = plugin('LocalNotifications');
    if (!localNotifications) return;

    const permissions = await localNotifications.checkPermissions();
    const result = permissions.display === 'prompt'
        ? await localNotifications.requestPermissions()
        : permissions;
    if (result.display !== 'granted') return;

    await localNotifications.schedule({
        notifications: [{
            id: Number(notification.id) || Math.floor(Date.now() / 1000),
            title: notification.title || 'TaskCheck',
            body: notification.body || notification.message || 'Nieuwe melding',
            extra: notification.data || {},
            schedule: { at: new Date(Date.now() + 250) },
        }],
    });
}

async function initializePushNotifications() {
    const push = plugin('PushNotifications');
    if (!push) return;

    await push.addListener('registration', ({ value }) => postNativeToken(value));
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

    let permissions = await push.checkPermissions();
    if (permissions.receive === 'prompt') {
        permissions = await push.requestPermissions();
    }
    if (permissions.receive === 'granted') {
        await push.register();
    }
}

async function initializeNativeFeatures() {
    if (!isNativeIos()) return;

    window.TaskCheckNative = {
        isIos: true,
        plugin,
        showLocalNotification,
    };

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

export { isNativeIos, plugin, showLocalNotification };
