<x-guest-layout>
    @php
        $taskcheckLogoPath = public_path('logos/taskcheck-logo.png');
        $taskcheckLogoVersion = file_exists($taskcheckLogoPath) ? filemtime($taskcheckLogoPath) : time();
    @endphp

    <div class="mb-6 flex justify-center">
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

    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-slate-950">Account aanmaken</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">
            Start uw 14-dagen gratis proefperiode. Geen creditcard vereist.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="space-y-2">
            <label for="company_name" class="block text-sm font-semibold text-slate-800">
                Bedrijfsnaam
            </label>
            <input id="company_name"
                   type="text"
                   name="company_name"
                   value="{{ old('company_name') }}"
                   required
                   autofocus
                   autocomplete="organization"
                   class="block w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100 @error('company_name') border-red-300 focus:border-red-500 focus:ring-red-100 @enderror"
                   placeholder="Bijv. Demo Organisatie">
            @error('company_name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="name" class="block text-sm font-semibold text-slate-800">
                Volledige naam
            </label>
            <input id="name"
                   type="text"
                   name="name"
                   value="{{ old('name') }}"
                   required
                   autocomplete="name"
                   class="block w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100 @error('name') border-red-300 focus:border-red-500 focus:ring-red-100 @enderror"
                   placeholder="Uw naam">
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="email" class="block text-sm font-semibold text-slate-800">
                E-mailadres
            </label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autocomplete="username"
                   class="block w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100 @error('email') border-red-300 focus:border-red-500 focus:ring-red-100 @enderror"
                   placeholder="naam@bedrijf.nl">
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="password" class="block text-sm font-semibold text-slate-800">
                Wachtwoord
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
            Account aanmaken
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Heeft u al een account?
        <a href="{{ route('login') }}" class="font-semibold text-blue-600 transition hover:text-blue-700">
            Log hier in
        </a>
    </p>

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
