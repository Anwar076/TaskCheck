<div class="fixed right-3 bottom-3 z-[9999]">
    <div class="relative">
        <button
            type="button"
            id="translate-toggle"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-lg hover:bg-slate-50 transition-colors"
            aria-expanded="false"
            aria-controls="translate-panel"
        >
            <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.5 2.4 4 5.6 4 9s-1.5 6.6-4 9c-2.5-2.4-4-5.6-4-9s1.5-6.6 4-9z"/>
            </svg>
            Taal
        </button>
        <div
            id="translate-panel"
            class="hidden absolute right-0 bottom-12 w-[220px] rounded-xl border border-slate-200 bg-white p-3 shadow-2xl"
        >
            <p class="mb-2 text-[11px] font-medium text-slate-500">Vertaal pagina</p>
            <div id="google_translate_element"></div>
        </div>
    </div>
</div>

<style>
.goog-te-banner-frame.skiptranslate { display: none !important; }
body { top: 0 !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('translate-toggle');
    const panel = document.getElementById('translate-panel');
    if (!toggle || !panel) return;

    const closePanel = () => {
        panel.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
    };

    toggle.addEventListener('click', function (event) {
        event.stopPropagation();
        const isHidden = panel.classList.contains('hidden');
        if (isHidden) {
            panel.classList.remove('hidden');
            toggle.setAttribute('aria-expanded', 'true');
        } else {
            closePanel();
        }
    });

    document.addEventListener('click', function (event) {
        if (!panel.contains(event.target) && !toggle.contains(event.target)) {
            closePanel();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closePanel();
    });
});

function googleTranslateElementInit() {
    if (!window.google || !window.google.translate) return;
    new google.translate.TranslateElement({
        pageLanguage: 'nl',
        includedLanguages: 'nl,en,de,fr,es,it,pt,ar,tr,pl,ro',
        autoDisplay: false
    }, 'google_translate_element');
}
</script>
<script async src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
