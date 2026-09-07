<!-- Navbar -->
@php
    $taskcheckLogoPath = public_path('logos/taskcheck-logo.png');
    $taskcheckLogoVersion = file_exists($taskcheckLogoPath) ? filemtime($taskcheckLogoPath) : time();
@endphp

<nav id="siteHeader" class="fixed top-0 z-50 w-full border-b border-slate-200/70 bg-white/90 backdrop-blur-2xl transition-all duration-300">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div id="siteHeaderShell" class="flex h-16 items-center justify-between sm:h-20">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <img
                    src="{{ asset('logos/taskcheck-logo.png') }}?v={{ $taskcheckLogoVersion }}"
                    alt="TaskCheck — Maak elke controle aantoonbaar"
                    width="640"
                    height="160"
                    fetchpriority="high"
                    decoding="async"
                    class="h-auto w-64 shrink-0 object-contain object-left transition-transform group-hover:scale-[1.03] sm:w-80 md:w-56 lg:w-80"
                >
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden items-center gap-8 md:flex lg:gap-10">
                <div class="flex items-center gap-6 lg:gap-8">
                    @foreach ([['Home', route('welcome'), request()->is('/')], ['Prijzen', route('pricing'), request()->is('pricing')], ['Blog', route('blog'), request()->is('blog*')], ['Contact', route('contact'), request()->is('contact')]] as [$label, $href, $active])
                        <a href="{{ $href }}" @if($active) aria-current="page" @endif class="site-nav-link {{ $active ? 'is-active' : '' }}">
                            <span>{{ $label }}</span>
                            <svg class="site-nav-stroke" aria-hidden="true" viewBox="0 0 80 8" preserveAspectRatio="none"><path d="M2 6 Q38 1 78 5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                        </a>
                    @endforeach
                </div>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ auth()->user()->homeDashboardUrl() }}" class="site-nav-cta">Dashboard <span class="site-nav-arrow" aria-hidden="true">→</span></a>
                    @else
                        <a href="{{ route('login') }}" class="site-nav-cta">Inloggen <span class="site-nav-arrow" aria-hidden="true">→</span></a>
                    @endauth
                @endif
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" class="rounded-xl p-2.5 text-slate-700 transition-colors hover:bg-blue-50 hover:text-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-600 md:hidden" aria-label="Open menu" aria-expanded="false">
                <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

</nav>

<!-- Mobile offcanvas navigation -->
<div id="mobileMenu" class="pointer-events-none fixed inset-0 z-[60] md:hidden" aria-hidden="true">
        <button id="mobileMenuBackdrop" type="button" class="absolute inset-0 bg-slate-950/55 opacity-0 backdrop-blur-sm transition-opacity duration-300" aria-label="Sluit menu"></button>
        <aside id="mobileMenuPanel" class="absolute inset-y-0 right-0 flex translate-x-full flex-col border-l border-slate-200 bg-white shadow-2xl transition-transform duration-300 ease-out" style="width:min(88vw,400px)">
            <div class="flex h-20 items-center justify-between border-b border-slate-100 px-6" style="padding-top:env(safe-area-inset-top)">
                <span class="text-xs font-bold uppercase tracking-[.18em] text-blue-600">Menu</span>
                <button id="mobileMenuClose" type="button" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl p-0 text-slate-700 transition-colors hover:bg-blue-50 hover:text-blue-700" aria-label="Sluit menu">
                    <svg class="block h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            </div>
            <div class="flex flex-1 flex-col overflow-y-auto px-5 py-6" style="padding-bottom:max(24px,env(safe-area-inset-bottom))">
                <div class="space-y-2">
                    @foreach ([['Home', route('welcome'), request()->is('/')], ['Prijzen', route('pricing'), request()->is('pricing')], ['Blog', route('blog'), request()->is('blog*')], ['Contact', route('contact'), request()->is('contact')]] as [$label, $href, $active])
                        <a href="{{ $href }}" @if($active) aria-current="page" @endif class="site-mobile-link {{ $active ? 'is-active' : '' }}">
                            <span>{{ $label }}</span>
                            <span class="site-nav-arrow" aria-hidden="true">↗</span>
                        </a>
                    @endforeach
                </div>
                <div class="mt-auto border-t border-slate-100 pt-5">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ auth()->user()->homeDashboardUrl() }}" class="site-nav-cta w-full justify-center">Dashboard <span class="site-nav-arrow" aria-hidden="true">→</span></a>
                        @else
                            <a href="{{ route('login') }}" class="site-nav-cta w-full justify-center">Inloggen <span class="site-nav-arrow" aria-hidden="true">→</span></a>
                        @endauth
                    @endif
                </div>
            </div>
        </aside>
    </div>

<script>
// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const siteHeader = document.getElementById('siteHeader');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuPanel = document.getElementById('mobileMenuPanel');
    const mobileMenuBackdrop = document.getElementById('mobileMenuBackdrop');
    const mobileMenuClose = document.getElementById('mobileMenuClose');

    requestAnimationFrame(function() {
        requestAnimationFrame(function() {
            document.querySelectorAll('[data-tc-text-reveal-load]').forEach(function(element) {
                element.classList.add('is-visible');
            });
        });
    });

    const textReveals = document.querySelectorAll('[data-tc-text-reveal]');
    if (textReveals.length) {
        if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            textReveals.forEach(function(element) { element.classList.add('is-visible'); });
        } else {
            const textRevealObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('is-visible');
                    textRevealObserver.unobserve(entry.target);
                });
            }, { threshold: 0.55 });
            textReveals.forEach(function(element) { textRevealObserver.observe(element); });
        }
    }

    if (mobileMenuBtn && mobileMenu && mobileMenuPanel && mobileMenuBackdrop) {
        const setMobileMenu = function(open) {
            mobileMenu.classList.toggle('pointer-events-none', !open);
            mobileMenuPanel.classList.toggle('translate-x-full', !open);
            mobileMenuBackdrop.classList.toggle('opacity-0', !open);
            mobileMenuBackdrop.classList.toggle('opacity-100', open);
            mobileMenu.setAttribute('aria-hidden', String(!open));
            mobileMenuBtn.setAttribute('aria-expanded', String(open));
            document.documentElement.classList.toggle('overflow-hidden', open);
            if (open) mobileMenuClose?.focus();
        };

        mobileMenuBtn.addEventListener('click', function() { setMobileMenu(true); });
        mobileMenuClose?.addEventListener('click', function() {
            setMobileMenu(false);
            mobileMenuBtn.focus();
        });
        mobileMenuBackdrop.addEventListener('click', function() { setMobileMenu(false); });
        mobileMenuPanel.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() { setMobileMenu(false); });
        });
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && mobileMenu.getAttribute('aria-hidden') === 'false') {
                setMobileMenu(false);
                mobileMenuBtn.focus();
            }
        });
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768 && mobileMenu.getAttribute('aria-hidden') === 'false') {
                setMobileMenu(false);
            }
        }, { passive: true });
    }

    // Subtle solid header on scroll for readability
    if (siteHeader) {
        let lastScrolledState = false;
        let ticking = false;

        const updateHeaderState = () => {
            const isScrolled = window.scrollY > 16;
            if (isScrolled !== lastScrolledState) {
                siteHeader.classList.toggle('bg-white/95', isScrolled);
                siteHeader.classList.toggle('shadow-md', isScrolled);
                lastScrolledState = isScrolled;
            }
            ticking = false;
        };

        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(updateHeaderState);
                ticking = true;
            }
        }, { passive: true });
    }
});
</script>
