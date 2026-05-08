<div class="mb-6 border-b border-slate-200">
    <nav class="-mb-px flex flex-wrap gap-2 sm:gap-4" aria-label="Instellingen tabs">
        <a href="{{ route('admin.settings.edit') }}"
           class="inline-flex items-center rounded-t-lg border-b-2 px-3 py-2 text-sm transition-colors {{ ($activeTab ?? '') === 'settings' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-slate-600 hover:border-slate-300 hover:text-slate-900 font-medium' }}">
            Instellingen
        </a>
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center rounded-t-lg border-b-2 px-3 py-2 text-sm transition-colors {{ ($activeTab ?? '') === 'users' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-slate-600 hover:border-slate-300 hover:text-slate-900 font-medium' }}">
            Gebruikers
        </a>
        <a href="{{ route('subscription.show') }}"
           class="inline-flex items-center rounded-t-lg border-b-2 px-3 py-2 text-sm transition-colors {{ ($activeTab ?? '') === 'subscription' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-slate-600 hover:border-slate-300 hover:text-slate-900 font-medium' }}">
            Abonnement
        </a>
    </nav>
</div>
