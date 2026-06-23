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

    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-slate-950">Wachtwoord vergeten</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">
            Vul uw e-mailadres in. U ontvangt een link om een nieuw wachtwoord te kiezen.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

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

        <button type="submit"
                class="flex w-full items-center justify-center rounded-lg border border-transparent bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
            Resetlink versturen
        </button>
    </form>

    <a href="{{ route('login') }}" class="mt-6 flex items-center justify-center gap-2 text-sm font-semibold text-slate-700 transition hover:text-blue-600">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
        </svg>
        Terug naar inloggen
    </a>
</x-guest-layout>
