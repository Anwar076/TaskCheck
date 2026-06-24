@php
    $translateVariant = $variant ?? 'floating';
    $translateButtonClass = $translateVariant === 'topbar'
        ? 'inline-flex h-10 items-center gap-2 rounded-xl px-2.5 text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition-colors'
        : 'inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-lg hover:bg-slate-50 transition-colors';
    $translatePanelClass = $translateVariant === 'topbar'
        ? 'hidden absolute right-0 mt-2 w-64 rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl z-50'
        : 'hidden absolute right-0 bottom-12 w-64 rounded-xl border border-slate-200 bg-white p-2 shadow-2xl';
    $languages = [
        ['code' => 'nl', 'label' => 'Nederlands', 'short' => 'NL', 'flag' => 'nl'],
        ['code' => 'en', 'label' => 'English', 'short' => 'EN', 'flag' => 'gb'],
        ['code' => 'fr', 'label' => 'Francais', 'short' => 'FR', 'flag' => 'fr'],
        ['code' => 'de', 'label' => 'Deutsch', 'short' => 'DE', 'flag' => 'de'],
        ['code' => 'es', 'label' => 'Espanol', 'short' => 'ES', 'flag' => 'es'],
        ['code' => 'tr', 'label' => 'Turkce', 'short' => 'TR', 'flag' => 'tr'],
        ['code' => 'pl', 'label' => 'Polski', 'short' => 'PL', 'flag' => 'pl'],
        ['code' => 'ar', 'label' => 'Arabic', 'short' => 'AR', 'flag' => 'sa'],
        ['code' => 'it', 'label' => 'Italiano', 'short' => 'IT', 'flag' => 'it'],
        ['code' => 'pt', 'label' => 'Portugues', 'short' => 'PT', 'flag' => 'pt'],
        ['code' => 'ro', 'label' => 'Romana', 'short' => 'RO', 'flag' => 'ro'],
    ];
    $flagCdnBase = 'https://flagcdn.com/24x18';
@endphp

<div class="{{ $translateVariant === 'floating' ? 'fixed right-3 bottom-3 z-[9999]' : 'relative' }} notranslate" translate="no" data-translate-root>
    <button
        type="button"
        class="{{ $translateButtonClass }}"
        aria-expanded="false"
        aria-label="Taal kiezen"
        title="Taal kiezen"
        data-translate-toggle
    >
        @if($translateVariant === 'topbar')
            <img
                src="{{ $flagCdnBase }}/nl.png"
                alt=""
                width="24"
                height="18"
                class="h-4 w-6 rounded-sm object-cover shadow-sm notranslate"
                translate="no"
                data-translate-current-flag
            >
            <span class="text-sm font-semibold leading-none notranslate" translate="no" data-translate-current-code>NL</span>
            <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
            </svg>
        @else
            <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.5 2.4 4 5.6 4 9s-1.5 6.6-4 9c-2.5-2.4-4-5.6-4-9s1.5-6.6 4-9z"/>
            </svg>
            <span>Taal</span>
            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
            </svg>
        @endif
    </button>

    <div class="{{ $translatePanelClass }} translate-language-panel" data-translate-panel>
        <div class="max-h-72 overflow-y-auto py-1">
            @foreach($languages as $language)
                <button
                    type="button"
                    class="flex w-full items-center gap-3 rounded-lg px-2.5 py-2 text-left text-sm font-medium text-slate-700 hover:bg-slate-50"
                    data-translate-lang="{{ $language['code'] }}"
                    data-translate-short="{{ $language['short'] }}"
                    data-translate-flag="{{ $language['flag'] }}"
                >
                    <span class="inline-flex h-[18px] w-6 shrink-0 overflow-hidden rounded-sm shadow-sm ring-1 ring-slate-200/80">
                        <img
                            src="{{ $flagCdnBase }}/{{ $language['flag'] }}.png"
                            alt=""
                            width="24"
                            height="18"
                            class="h-full w-full object-cover"
                            loading="lazy"
                        >
                    </span>
                    <span class="min-w-0 flex-1 truncate">{{ $language['label'] }}</span>
                    <svg class="hidden h-4 w-4 text-blue-600" data-translate-check="{{ $language['code'] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                </button>
            @endforeach
        </div>
        <div id="google_translate_element" class="translate-widget-host"></div>
    </div>
</div>

@once
<style>
.goog-te-banner-frame,
.goog-te-banner-frame.skiptranslate,
.goog-te-gadget-icon,
.goog-te-balloon-frame,
iframe.skiptranslate,
#goog-gt-tt { display: none !important; visibility: hidden !important; }
html,
body { top: 0 !important; margin-top: 0 !important; }
.goog-te-gadget { height: 0 !important; overflow: hidden !important; }
#google_translate_element .skiptranslate,
iframe.skiptranslate {
    font-size: 0 !important;
}
.translate-language-panel {
    font-size: 0.875rem;
}
.translate-language-panel img {
    display: block;
}
.translate-widget-host {
    position: absolute;
    width: 1px;
    height: 1px;
    overflow: hidden;
    opacity: 0;
    pointer-events: none;
}
</style>

<script>
(function () {
    const storageKey = 'taskcheck:translate-language';
    const includedLanguages = 'nl,en,de,fr,es,it,pt,ar,tr,pl,ro';
    const flagCdnBase = @json($flagCdnBase);

    function flagUrl(code) {
        return `${flagCdnBase}/${code || 'nl'}.png`;
    }

    function setCookie(name, value) {
        const expires = new Date();
        expires.setFullYear(expires.getFullYear() + 1);
        document.cookie = `${name}=${value}; expires=${expires.toUTCString()}; path=/`;
        document.cookie = `${name}=${value}; expires=${expires.toUTCString()}; path=/; domain=${window.location.hostname}`;
    }

    function clearTranslateCookie() {
        document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
        document.cookie = `googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=${window.location.hostname}`;
    }

    function getCurrentLanguage() {
        return localStorage.getItem(storageKey) || 'nl';
    }

    function markCurrentLanguage() {
        const current = getCurrentLanguage();
        document.querySelectorAll('[data-translate-check]').forEach((icon) => {
            icon.classList.toggle('hidden', icon.dataset.translateCheck !== current);
        });
        document.querySelectorAll('[data-translate-root]').forEach((root) => {
            const selected = root.querySelector(`[data-translate-lang="${current}"]`) || root.querySelector('[data-translate-lang="nl"]');
            const flagCode = selected?.dataset.translateFlag || 'nl';
            const short = selected?.dataset.translateShort || 'NL';
            const flagEl = root.querySelector('[data-translate-current-flag]');
            const codeEl = root.querySelector('[data-translate-current-code]');

            if (flagEl) {
                if (flagEl.tagName === 'IMG') {
                    flagEl.src = flagUrl(flagCode);
                } else {
                    flagEl.textContent = flagCode.toUpperCase();
                }
            }
            if (codeEl) codeEl.textContent = short;

            root.querySelectorAll('[data-translate-lang]').forEach((button) => {
                button.classList.toggle('bg-slate-100', button.dataset.translateLang === current);
                button.classList.toggle('text-slate-900', button.dataset.translateLang === current);
            });
        });
    }

    function closeTranslatePanels(exceptRoot = null) {
        document.querySelectorAll('[data-translate-root]').forEach((root) => {
            if (exceptRoot && root === exceptRoot) return;
            root.querySelector('[data-translate-panel]')?.classList.add('hidden');
            root.querySelector('[data-translate-toggle]')?.setAttribute('aria-expanded', 'false');
        });
    }

    function applyLanguage(language, reloadOriginal = true) {
        const previousLanguage = getCurrentLanguage();
        localStorage.setItem(storageKey, language);
        markCurrentLanguage();

        if (language === 'nl') {
            clearTranslateCookie();
            if (reloadOriginal && previousLanguage !== 'nl') {
                window.location.reload();
            }
            return;
        }

        setCookie('googtrans', `/nl/${language}`);
        window.location.reload();
    }

    function bindTranslateDropdowns() {
        document.querySelectorAll('[data-translate-root]').forEach((root) => {
            const toggle = root.querySelector('[data-translate-toggle]');
            const panel = root.querySelector('[data-translate-panel]');
            if (!toggle || !panel || root.dataset.translateBound === 'true') return;

            root.dataset.translateBound = 'true';
            toggle.addEventListener('click', (event) => {
                event.stopPropagation();
                const isHidden = panel.classList.contains('hidden');
                closeTranslatePanels(root);
                panel.classList.toggle('hidden', !isHidden);
                toggle.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
            });

            root.querySelectorAll('[data-translate-lang]').forEach((button) => {
                button.addEventListener('click', () => {
                    applyLanguage(button.dataset.translateLang);
                    closeTranslatePanels();
                });
            });
        });

        markCurrentLanguage();
    }

    document.addEventListener('DOMContentLoaded', function () {
        bindTranslateDropdowns();
        hideGoogleTranslateChrome();

        document.addEventListener('click', () => closeTranslatePanels());
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') closeTranslatePanels();
        });
    });

    function hideGoogleTranslateChrome() {
        document.documentElement.style.top = '0px';
        document.documentElement.style.marginTop = '0px';
        document.body.style.top = '0px';
        document.body.style.marginTop = '0px';

        document.querySelectorAll('.goog-te-banner-frame, iframe.skiptranslate, .goog-te-balloon-frame, #goog-gt-tt').forEach((element) => {
            element.style.display = 'none';
            element.style.visibility = 'hidden';
        });
    }

    new MutationObserver(hideGoogleTranslateChrome).observe(document.documentElement, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['style', 'class'],
    });

    window.googleTranslateElementInit = function () {
        if (!window.google || !window.google.translate) return;
        new window.google.translate.TranslateElement({
            pageLanguage: 'nl',
            includedLanguages,
            autoDisplay: false
        }, 'google_translate_element');

        const storedLanguage = getCurrentLanguage();
        if (storedLanguage !== 'nl') {
            const select = document.querySelector('.goog-te-combo');
            if (select) {
                window.setTimeout(() => {
                    select.value = storedLanguage;
                    select.dispatchEvent(new Event('change'));
                    hideGoogleTranslateChrome();
                }, 400);
            }
        }
    };
})();
</script>
<script async src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
@endonce
