<x-guest-layout>
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100 p-8 text-center">
        <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Welkom Terug</h1>
        <p class="text-gray-600">Log in op uw TaskCheck account</p>
    </div>

    <!-- Login Form -->
    <div class="p-8">
        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">

            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6" id="login-form">
            @csrf
            <input type="hidden" name="remember" value="1">

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    E-mailadres
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           autocomplete="username"
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                           placeholder="Voer uw e-mailadres in">
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Wachtwoord
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required 
                           autocomplete="current-password"
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                           placeholder="Voer uw wachtwoord in">
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" 
                           type="checkbox" 
                           name="remember"
                           value="1"
                           checked
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                        Onthoud mij
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" 
                       class="text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors">
                        Wachtwoord vergeten?
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <div>
                <button type="submit" 
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Inloggen
                </button>
            </div>
        </form>

        <!-- Register Button -->
        <div class="mt-6">
            <a href="{{ route('register') }}" 
               class="w-full flex justify-center items-center py-3 px-4 border-2 border-blue-600 rounded-xl shadow-sm text-sm font-semibold text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Registreren
            </a>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600">
                Nog geen account? 
                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                    Maak er een aan
                </a>
                <span class="mx-2">|</span>
                <a href="#" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                    Hulp nodig?
                </a>
            </p>
        </div>
    </div>
    
    <!-- Prevent CSRF token expiry issues -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('login-form');
        
        // Force reload on navigation back to login (prevents cached CSRF token)
        if (performance.navigation && performance.navigation.type === 2) {
            window.location.reload(true);
        } else if (window.performance.getEntriesByType('navigation')[0]?.type === 'back_forward') {
            window.location.reload(true);
        }
        
        // Check if coming from logout
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('logout')) {
            // Force hard refresh to get new CSRF token
            window.location.href = window.location.pathname + (urlParams.has('source') ? '?source=' + urlParams.get('source') : '');
        }
        
        if (loginForm) {
            let formSubmitting = false;
            
            loginForm.addEventListener('submit', async function(e) {
                if (formSubmitting) {
                    return; // Already submitting
                }
                
                e.preventDefault();
                formSubmitting = true;
                
                try {
                    // Fetch fresh CSRF token
                    const response = await fetch('{{ route('refresh-csrf') }}', {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        
                        // Update CSRF token in form
                        const csrfInput = loginForm.querySelector('input[name="_token"]');
                        if (csrfInput && data.token) {
                            csrfInput.value = data.token;
                        }
                        
                        // Update meta tag
                        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                        if (csrfMeta && data.token) {
                            csrfMeta.setAttribute('content', data.token);
                        }
                    }
                } catch (error) {
                    console.log('CSRF refresh attempted, continuing with existing token');
                }
                
                // Submit the form (with fresh or existing token)
                loginForm.submit();
            });
        }
    });
    </script>
</x-guest-layout>
