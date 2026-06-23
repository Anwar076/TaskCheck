<x-guest-layout>
    @php
        $taskcheckLogoPath = public_path('logos/taskcheck-logo.png');
        $taskcheckLogoVersion = file_exists($taskcheckLogoPath) ? filemtime($taskcheckLogoPath) : time();
    @endphp

    <div class="mb-8 flex justify-center">
        <a href="{{ route('welcome') }}" class="inline-flex">
            <img
                src="{{ asset('logos/taskcheck-logo.png') }}?v={{ $taskcheckLogoVersion }}"
                alt="TaskCheck - Maak elke controle aantoonbaar"
                width="640"
                height="160"
                class="h-16 w-auto object-contain"
                decoding="async"
                fetchpriority="high"
            >
        </a>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-950">Nieuw wachtwoord instellen</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">
            Kies een sterk nieuw wachtwoord voor uw TaskCheck-account.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="space-y-2">
            <label for="email" class="block text-sm font-semibold text-slate-800">
                E-mailadres
            </label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email', $request->email) }}"
                   required
                   autofocus
                   autocomplete="username"
                   class="block w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100 @error('email') border-red-300 focus:border-red-500 focus:ring-red-100 @enderror"
                   placeholder="naam@bedrijf.nl">
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="password" class="block text-sm font-semibold text-slate-800">
                Nieuw wachtwoord
            </label>
            <div class="relative">
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="new-password"
                       class="block w-full rounded-lg border border-slate-200 bg-white px-4 py-3 pr-11 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100 @error('password') border-red-300 focus:border-red-500 focus:ring-red-100 @enderror"
                       placeholder="Minimaal 8 tekens">
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 inline-flex w-11 items-center justify-center text-slate-400 transition hover:text-slate-600"
                    aria-label="Wachtwoord tonen"
                    data-password-toggle
                    data-password-target="password"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="password_confirmation" class="block text-sm font-semibold text-slate-800">
                Bevestig wachtwoord
            </label>
            <div class="relative">
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       class="block w-full rounded-lg border border-slate-200 bg-white px-4 py-3 pr-11 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100 @error('password_confirmation') border-red-300 focus:border-red-500 focus:ring-red-100 @enderror"
                       placeholder="Herhaal uw wachtwoord">
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 inline-flex w-11 items-center justify-center text-slate-400 transition hover:text-slate-600"
                    aria-label="Wachtwoord tonen"
                    data-password-toggle
                    data-password-target="password_confirmation"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z"/>
                    </svg>
                </button>
            </div>
            @error('password_confirmation')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="flex w-full items-center justify-center rounded-lg border border-transparent bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
            Wachtwoord opslaan
        </button>
    </form>

    <a href="{{ route('login') }}" class="mt-6 flex items-center justify-center gap-2 text-sm font-semibold text-slate-700 transition hover:text-blue-600">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
        </svg>
        Terug naar inloggen
    </a>

    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-password-target');
                const passwordInput = targetId ? document.getElementById(targetId) : null;
                if (!passwordInput) return;

                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                button.setAttribute('aria-label', isHidden ? 'Wachtwoord verbergen' : 'Wachtwoord tonen');
            });
        });
    </script>
</x-guest-layout>
