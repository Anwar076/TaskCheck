<x-guest-layout>
    @php
        $taskcheckLogoPath = public_path('logos/taskcheck-logo.png');
        $taskcheckLogoVersion = file_exists($taskcheckLogoPath) ? filemtime($taskcheckLogoPath) : time();
    @endphp

    <div class="mb-8 flex justify-center">
        <a href="{{ route('welcome') }}" class="inline-flex">
            <img
                src="{{ asset('logos/taskcheck-logo.png') }}?v={{ $taskcheckLogoVersion }}"
                alt="TaskCheck — Maak elke controle aantoonbaar"
                width="640"
                height="160"
                class="h-16 w-auto object-contain"
                decoding="async"
                fetchpriority="high"
            >
        </a>
    </div>

    @if (session('status'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4" id="login-form">
            @csrf
            <input type="hidden" name="remember" value="1">

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-semibold text-slate-800">
                    E-mailadres
                </label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       autocomplete="username"
                       class="block w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100 @error('email') border-red-300 focus:border-red-500 focus:ring-red-100 @enderror"
                       placeholder="naam@bedrijf.nl">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-semibold text-slate-800">
                    Wachtwoord
                </label>
                <div class="relative">
                    <input id="password"
                           type="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           class="block w-full rounded-lg border border-slate-200 bg-white px-4 py-3 pr-11 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100 @error('password') border-red-300 focus:border-red-500 focus:ring-red-100 @enderror"
                           placeholder="********">
                    <button
                        type="button"
                        class="absolute inset-y-0 right-0 inline-flex w-11 items-center justify-center text-slate-400 transition hover:text-slate-600"
                        aria-label="Wachtwoord tonen"
                        data-password-toggle
                    >
                        <svg class="h-5 w-5" data-password-eye fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between gap-4">
                <label for="remember_me" class="flex items-center text-sm text-slate-600">
                    <input id="remember_me" 
                           type="checkbox" 
                           name="remember"
                           value="1"
                           checked
                           class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2">Onthoud mij</span>
                </label>

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
                        class="flex w-full items-center justify-center rounded-lg border border-transparent bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Inloggen
                </button>
            </div>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Nog geen account?
        <a href="{{ route('register') }}" class="font-semibold text-blue-600 transition hover:text-blue-700">
            Maak er een aan
        </a>
    </p>
    
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

            document.querySelectorAll('[data-password-toggle]').forEach((button) => {
                button.addEventListener('click', () => {
                    const passwordInput = document.getElementById('password');
                    if (!passwordInput) return;

                    const isHidden = passwordInput.type === 'password';
                    passwordInput.type = isHidden ? 'text' : 'password';
                    button.setAttribute('aria-label', isHidden ? 'Wachtwoord verbergen' : 'Wachtwoord tonen');
                });
            });
            
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
