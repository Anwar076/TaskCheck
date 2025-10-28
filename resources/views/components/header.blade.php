<!-- Navbar -->
<nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-md border-b border-gray-200/40">
    <div class="max-w-7xl mx-auto px-6 flex justify-between items-center h-16">
        <div class="flex items-center space-x-3">
            <a href="{{ url('/') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <span class="text-xl font-extrabold">TaskCheck</span>
            </a>
        </div>
        
        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-6">
            <a href="{{ url('/') }}" class="text-gray-700 hover:text-primary-600 font-medium {{ request()->is('/') ? 'text-primary-600' : '' }}">Home</a>
            <a href="{{ route('features') }}" class="text-gray-700 hover:text-primary-600 font-medium {{ request()->is('features') ? 'text-primary-600' : '' }}">Functies</a>
            <a href="{{ route('pricing') }}" class="text-gray-700 hover:text-primary-600 font-medium {{ request()->is('pricing') ? 'text-primary-600' : '' }}">Prijzen</a>
            <a href="{{ route('about') }}" class="text-gray-700 hover:text-primary-600 font-medium {{ request()->is('about') ? 'text-primary-600' : '' }}">Over</a>
            <a href="{{ route('contact') }}" class="text-gray-700 hover:text-primary-600 font-medium {{ request()->is('contact') ? 'text-primary-600' : '' }}">Contact</a>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-primary-600 font-medium">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-gradient text-white px-5 py-2 rounded-lg font-medium">Inloggen</a>
                @endauth
            @endif
        </div>

        <!-- Mobile Menu Button -->
        <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Navigation Menu -->
    <div id="mobileMenu" class="md:hidden bg-white border-t border-gray-200 hidden">
        <div class="px-6 py-4 space-y-4">
            <a href="{{ url('/') }}" class="block text-gray-700 hover:text-primary-600 font-medium py-2 {{ request()->is('/') ? 'text-primary-600' : '' }}">Home</a>
            <a href="{{ route('features') }}" class="block text-gray-700 hover:text-primary-600 font-medium py-2 {{ request()->is('features') ? 'text-primary-600' : '' }}">Functies</a>
            <a href="{{ route('pricing') }}" class="block text-gray-700 hover:text-primary-600 font-medium py-2 {{ request()->is('pricing') ? 'text-primary-600' : '' }}">Prijzen</a>
            <a href="{{ route('about') }}" class="block text-gray-700 hover:text-primary-600 font-medium py-2 {{ request()->is('about') ? 'text-primary-600' : '' }}">Over</a>
            <a href="{{ route('contact') }}" class="block text-gray-700 hover:text-primary-600 font-medium py-2 {{ request()->is('contact') ? 'text-primary-600' : '' }}">Contact</a>
            <a href="{{ route('blog') }}" class="block text-gray-700 hover:text-primary-600 font-medium py-2">Blog</a>
            <a href="{{ route('careers') }}" class="block text-gray-700 hover:text-primary-600 font-medium py-2">Vacatures</a>
            <a href="{{ route('help') }}" class="block text-gray-700 hover:text-primary-600 font-medium py-2">Hulp</a>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="block text-gray-700 hover:text-primary-600 font-medium py-2">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block btn-gradient text-white px-4 py-2 rounded-lg font-medium text-center">Inloggen</a>
                @endauth
            @endif
        </div>
    </div>
</nav>

<script>
// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>