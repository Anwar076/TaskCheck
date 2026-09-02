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
                    class="h-auto w-64 shrink-0 object-contain object-left transition-transform group-hover:scale-[1.03] sm:w-80"
                >
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden items-center gap-5 md:flex">
                <div class="flex items-center gap-1 rounded-xl bg-slate-50/80 p-1">
                    @foreach ([['Home', route('welcome'), request()->is('/')], ['Prijzen', route('pricing'), request()->is('pricing')], ['Blog', route('blog'), request()->is('blog*')], ['Contact', route('contact'), request()->is('contact')]] as [$label, $href, $active])
                        <a href="{{ $href }}" class="group relative rounded-lg px-3.5 py-2 text-sm font-semibold tracking-[-.01em] transition-all duration-200 {{ $active ? 'bg-white text-blue-700 shadow-sm ring-1 ring-slate-200/70' : 'text-slate-600 hover:bg-white/80 hover:text-slate-950' }}">
                            <span>{{ $label }}</span>
                            <span class="absolute inset-x-3 bottom-1 h-px origin-left rounded-full bg-gradient-to-r from-blue-600 to-indigo-500 transition-transform duration-300 {{ $active ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></span>
                        </a>
                    @endforeach
                </div>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ auth()->user()->homeDashboardUrl() }}" class="group inline-flex items-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/10 transition-all hover:-translate-y-px hover:bg-blue-700">Dashboard <span class="transition-transform group-hover:translate-x-0.5">→</span></a>
                    @else
                        <a href="{{ route('login') }}" class="group inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition-all hover:-translate-y-px hover:shadow-blue-600/30">Inloggen <span class="transition-transform group-hover:translate-x-0.5">→</span></a>
                    @endauth
                @endif
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" class="rounded-xl border border-slate-200 bg-slate-50 p-2.5 shadow-sm transition-all hover:border-blue-200 hover:bg-blue-50 md:hidden" aria-label="Open menu" aria-expanded="false">
                <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div id="mobileMenu" class="hidden border-t border-slate-200/70 bg-white/95 shadow-xl backdrop-blur-2xl md:hidden">
        <div class="mx-auto max-w-7xl space-y-1 px-4 py-4 sm:px-6">
            <a href="{{ route('welcome') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->is('/') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100' }}">Home</a>
            <a href="{{ route('pricing') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->is('pricing') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100' }}">Prijzen</a>
            <a href="{{ route('blog') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->is('blog*') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100' }}">Blog</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->is('contact') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100' }}">Contact</a>

            <div class="pt-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ auth()->user()->homeDashboardUrl() }}" class="block text-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-slate-900 text-white hover:bg-slate-800 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block text-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700 transition">Inloggen</a>
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>

<script>
// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const siteHeader = document.getElementById('siteHeader');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');

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

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            mobileMenuBtn.setAttribute('aria-expanded', String(!mobileMenu.classList.contains('hidden')));
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
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
