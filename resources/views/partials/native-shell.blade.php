<script>
(function () {
    var params = new URLSearchParams(window.location.search || '');
    var native = params.get('source') === 'pwa' || !!(window.Capacitor && (
        typeof window.Capacitor.isNativePlatform === 'function'
            ? window.Capacitor.isNativePlatform()
            : window.Capacitor.isNative
    ));
    if (!native && !(/Capacitor|wv/i.test(navigator.userAgent) && /Android/i.test(navigator.userAgent))) {
        return;
    }
    document.documentElement.classList.add('is-native-app');

    function pointLogoutFormsToLogin() {
        document.querySelectorAll('form[action*="/logout"]').forEach(function (form) {
            try {
                var url = new URL(form.getAttribute('action'), window.location.origin);
                url.searchParams.set('source', 'pwa');
                form.setAttribute('action', url.pathname + url.search);
            } catch (e) {}
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', pointLogoutFormsToLogin, { once: true });
    } else {
        pointLogoutFormsToLogin();
    }
})();
</script>
