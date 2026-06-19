@extends($profileLayout ?? 'layouts.admin')

@section('page-title', 'Profiel')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <span class="text-slate-900 font-semibold truncate">Profiel</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-6 sm:px-8 py-6 sm:py-8 text-white">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-lg font-bold shadow-sm ring-1 ring-white/20">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <h1 class="text-2xl sm:text-3xl font-bold truncate">Profiel</h1>
                                <p class="mt-1 text-sm sm:text-base text-blue-100 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                        @php
                            $dashboardRoute = $user->isSuperAdmin()
                                ? route('super-admin.dashboard')
                                : ($user->isAdmin() ? route('admin.dashboard') : route('employee.dashboard'));
                        @endphp
                        <a href="{{ $dashboardRoute }}"
                           class="inline-flex items-center justify-center rounded-xl border border-white/30 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/20 transition-colors">
                            Terug naar dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                Profiel opgeslagen.
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                Wachtwoord bijgewerkt.
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-2 xl:items-start">
            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="border-b border-slate-100 bg-blue-50/60 px-6 py-5">
                    <h2 class="text-xl font-bold text-slate-900">Profielgegevens</h2>
                    <p class="mt-1 text-sm text-slate-600">Pas je naam en e-mailadres aan.</p>
                </div>

                <form id="send-verification" method="POST" action="{{ route('verification.send') }}" class="hidden">
                    @csrf
                </form>

                <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Naam</label>
                        <input id="name" name="name" type="text" required autofocus autocomplete="name"
                               value="{{ old('name', $user->name) }}"
                               class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3">
                        @error('name')
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">E-mailadres</label>
                        <input id="email" name="email" type="email" required autocomplete="username"
                               value="{{ old('email', $user->email) }}"
                               class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3">
                        @error('email')
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <p class="mt-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                                Je e-mailadres is nog niet geverifieerd.
                                <button form="send-verification" class="font-semibold underline underline-offset-2">Verificatiemail opnieuw sturen</button>
                            </p>
                        @endif
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                        Profiel opslaan
                    </button>
                </form>
            </section>

            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="border-b border-slate-100 bg-cyan-50/70 px-6 py-5">
                    <h2 class="text-xl font-bold text-slate-900">Wachtwoord wijzigen</h2>
                    <p class="mt-1 text-sm text-slate-600">Gebruik een sterk wachtwoord om je account te beveiligen.</p>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="update_password_current_password" class="block text-sm font-semibold text-slate-700 mb-2">Huidig wachtwoord</label>
                        <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                               class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3">
                        @foreach ($errors->updatePassword->get('current_password') as $message)
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @endforeach
                    </div>

                    <div>
                        <label for="update_password_password" class="block text-sm font-semibold text-slate-700 mb-2">Nieuw wachtwoord</label>
                        <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                               class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3">
                        @foreach ($errors->updatePassword->get('password') as $message)
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @endforeach
                    </div>

                    <div>
                        <label for="update_password_password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Bevestig wachtwoord</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                               class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3">
                        @foreach ($errors->updatePassword->get('password_confirmation') as $message)
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @endforeach
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                        Wachtwoord opslaan
                    </button>
                </form>
            </section>
        </div>

        <section class="mt-6 bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
            <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Account verwijderen</h2>
                    <p class="mt-1 text-sm text-slate-600">Dit verwijdert je account definitief. Gebruik dit alleen als je zeker bent.</p>
                </div>
                <button type="button" data-open-delete-profile class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-semibold text-red-700 hover:bg-red-100 transition-colors">
                    Account verwijderen
                </button>
            </div>
        </section>
    </div>
</div>

<div id="delete-profile-modal" class="fixed inset-0 z-[80] hidden items-center justify-center bg-slate-950/60 p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl">
        <form method="POST" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('DELETE')

            <h2 class="text-xl font-bold text-slate-900">Account verwijderen?</h2>
            <p class="mt-2 text-sm text-slate-600">Vul je wachtwoord in om te bevestigen dat je je account definitief wilt verwijderen.</p>

            <div class="mt-5">
                <label for="delete_profile_password" class="block text-sm font-semibold text-slate-700 mb-2">Wachtwoord</label>
                <input id="delete_profile_password" name="password" type="password"
                       class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500 px-4 py-3">
                @foreach ($errors->userDeletion->get('password') as $message)
                    <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                @endforeach
            </div>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" data-close-delete-profile class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    Annuleren
                </button>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700 transition-colors">
                    Definitief verwijderen
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var modal = document.getElementById('delete-profile-modal');
        var open = document.querySelector('[data-open-delete-profile]');
        var close = document.querySelector('[data-close-delete-profile]');

        function showModal() {
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function hideModal() {
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        open?.addEventListener('click', showModal);
        close?.addEventListener('click', hideModal);
        modal?.addEventListener('click', function (event) {
            if (event.target === modal) hideModal();
        });

        @if ($errors->userDeletion->isNotEmpty())
            showModal();
        @endif
    })();
</script>
@endpush
