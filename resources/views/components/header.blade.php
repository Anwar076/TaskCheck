<!-- Navbar -->
@php
    $taskcheckLogoPath = public_path('logos/taskcheck-logo.png');
    $taskcheckLogoVersion = file_exists($taskcheckLogoPath) ? filemtime($taskcheckLogoPath) : time();
@endphp

<nav id="siteHeader" class="fixed top-0 z-50 w-full border-b border-slate-200/60 bg-white/80 shadow-[0_1px_0_rgba(15,23,42,0.02)] backdrop-blur-2xl transition-all duration-300">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between sm:h-20">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <img
                    src="{{ asset('logos/taskcheck-logo.png') }}?v={{ $taskcheckLogoVersion }}"
                    alt="TaskCheck — Maak elke controle aantoonbaar"
                    width="640"
                    height="160"
                    fetchpriority="high"
                    decoding="async"
                    class="h-12 w-auto shrink-0 object-contain object-left transition-transform group-hover:scale-[1.03] sm:h-14"
                >
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden items-center gap-7 md:flex">
                <div class="relative flex items-center gap-8 after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-slate-200/80">
                    @foreach ([['Home', route('welcome'), request()->is('/')], ['Prijzen', route('pricing'), request()->is('pricing')], ['Blog', route('blog'), request()->is('blog*')], ['Contact', route('contact'), request()->is('contact')]] as [$label, $href, $active])
                        <a href="{{ $href }}" class="group relative z-10 py-3 text-sm font-semibold tracking-[-.01em] transition-all duration-300 hover:-translate-y-px {{ $active ? 'text-blue-700' : 'text-slate-600 hover:text-slate-950' }}">
                            <span @if($active) style="background:linear-gradient(90deg,#1d4ed8,#4f46e5);-webkit-background-clip:text;background-clip:text;color:transparent" @endif>{{ $label }}</span>
                            <span class="absolute inset-x-0 bottom-0 h-0.5 origin-left rounded-full bg-gradient-to-r from-blue-600 via-indigo-500 to-violet-500 transition-transform duration-300 {{ $active ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></span>
                        </a>
                    @endforeach
                </div>
                @if (Route::has('login'))
                    <span class="h-7 w-px bg-slate-200" aria-hidden="true"></span>
                    @auth
                        <a href="{{ auth()->user()->homeDashboardUrl() }}" class="group inline-flex items-center gap-2 rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-px hover:bg-blue-700">Dashboard <span class="transition-transform group-hover:translate-x-0.5">→</span></a>
                    @else
                        <a href="{{ route('login') }}" class="group inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-700">Inloggen <span class="transition-transform group-hover:translate-x-0.5">→</span></a>
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
                siteHeader.classList.toggle('shadow-sm', isScrolled);
                siteHeader.classList.toggle('bg-white/70', !isScrolled);
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
