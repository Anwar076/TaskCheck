<script>
(function () {
    var native = !!(window.Capacitor && (
        typeof window.Capacitor.isNativePlatform === 'function'
            ? window.Capacitor.isNativePlatform()
            : window.Capacitor.isNative
    ));
    if (!native && !(/Capacitor|wv/i.test(navigator.userAgent) && /Android/i.test(navigator.userAgent))) {
        return;
    }
    document.documentElement.classList.add('is-native-app');
})();
</script>
