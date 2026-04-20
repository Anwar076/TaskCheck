<!-- Navbar -->
<nav id="siteHeader" class="fixed top-0 w-full z-50 border-b border-white/40 bg-white/70 backdrop-blur-xl transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex justify-between items-center h-16">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <img
                    src="{{ asset('logos/taskcheck-logo.png') }}"
                    alt="TaskCheck logo"
                    class="h-12 sm:h-14 w-auto shrink-0 transition-transform group-hover:scale-[1.03]"
                >
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-2">
                <a href="{{ route('welcome') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->is('/') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">Home</a>
                <a href="{{ route('pricing') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->is('pricing') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">Prijzen</a>
                <a href="{{ route('blog') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->is('blog*') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">Blog</a>
                <a href="{{ route('contact') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->is('contact') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">Contact</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="ml-2 inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-slate-900 text-white hover:bg-slate-800 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="ml-2 inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700 shadow-sm transition">Inloggen</a>
                    @endauth
                @endif
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg bg-white/80 border border-slate-200 hover:bg-slate-50 transition-colors" aria-label="Open menu">
                <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div id="mobileMenu" class="md:hidden hidden border-t border-slate-200 bg-white/95 backdrop-blur-xl">
        <div class="px-4 py-4 space-y-1">
            <a href="{{ route('welcome') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->is('/') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100' }}">Home</a>
            <a href="{{ route('pricing') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->is('pricing') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100' }}">Prijzen</a>
            <a href="{{ route('blog') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->is('blog*') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100' }}">Blog</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->is('contact') ? 'bg-blue-50 text-blue-700' : 'text-slate-700 hover:bg-slate-100' }}">Contact</a>

            <div class="pt-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="block text-center px-4 py-2.5 rounded-lg text-sm font-semibold bg-slate-900 text-white hover:bg-slate-800 transition">Dashboard</a>
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
        window.addEventListener('scroll', function() {
            if (window.scrollY > 16) {
                siteHeader.classList.add('bg-white/95', 'shadow-sm');
                siteHeader.classList.remove('bg-white/70');
            } else {
                siteHeader.classList.remove('bg-white/95', 'shadow-sm');
                siteHeader.classList.add('bg-white/70');
            }
        });
    }
});
</script>