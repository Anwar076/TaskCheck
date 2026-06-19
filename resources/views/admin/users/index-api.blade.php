@extends('layouts.admin')

@section('page-title', 'Gebruikersbeheer')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <span class="text-slate-900 font-semibold truncate">Gebruikers</span>
@endsection

@section('content')
@php
    $companyId = auth()->user()->company_id ?? null;
    $allUsers = $companyId 
        ? \App\Models\User::where('company_id', $companyId)->latest()->get()
        : collect();
    $fallbackStats = [
        'total_users' => $allUsers->count(),
        'admin_users' => $allUsers->where('role', 'admin')->count(),
        'employee_users' => $allUsers->whereIn('role', ['employee', 'admin'])->count(),
        'active_users' => $allUsers->where('is_active', true)->count(),
    ];
@endphp

<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">

        {{-- Hero --}}
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Gebruikersbeheer</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">Beheer medewerkers en beheerders ({{ $fallbackStats['total_users'] }} gebruikers)</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.create') }}" data-onboarding-target="add-user" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-700 text-sm font-semibold rounded-xl hover:bg-blue-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Gebruiker toevoegen
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.settings.tabs', ['activeTab' => 'users'])

        {{-- Stats --}}
        <div id="users-stats" class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900" id="total-users">{{ $fallbackStats['total_users'] }}</p>
                        <p class="text-sm text-slate-600">Totaal</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900" id="admin-users">{{ $fallbackStats['admin_users'] }}</p>
                        <p class="text-sm text-slate-600">Beheerders</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-violet-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-violet-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900" id="employee-users">{{ $fallbackStats['employee_users'] }}</p>
                        <p class="text-sm text-slate-600">Medewerkers</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900" id="active-users">{{ $fallbackStats['active_users'] }}</p>
                        <p class="text-sm text-slate-600">Actief</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Zoeken en filter --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6 sm:mb-8">
            <div class="px-4 sm:px-6 py-4 sm:py-5">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex-1 w-full lg:max-w-md">
                        <label for="search-input" class="sr-only">Zoeken</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                            </div>
                            <input type="search" id="search-input" placeholder="Zoek op naam of e-mail..." autocomplete="off"
                                class="block w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-2">
                            <label for="role-filter" class="text-sm text-slate-600 whitespace-nowrap">Rol:</label>
                            <select id="role-filter" class="px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white min-w-[120px]">
                                <option value="">Alle rollen</option>
                                <option value="admin">Beheerder</option>
                                <option value="employee">Medewerker</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="status-filter" class="text-sm text-slate-600 whitespace-nowrap">Status:</label>
                            <select id="status-filter" class="px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white min-w-[120px]">
                                <option value="">Alle statussen</option>
                                <option value="true">Actief</option>
                                <option value="false">Inactief</option>
                            </select>
                        </div>
                        <button type="button" id="refresh-btn" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" title="Vernieuwen">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div id="users-table" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Gebruiker</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Rol</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Afdeling</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Aangemaakt</th>
                            <th class="px-4 sm:px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Acties</th>
                        </tr>
                    </thead>
                    <tbody id="users-tbody" class="divide-y divide-slate-200">
                        @forelse($allUsers as $user)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 sm:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                                            <span class="text-sm font-semibold text-white">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-medium text-slate-900 truncate">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-500 truncate">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $user->role === 'admin' ? 'bg-emerald-100 text-emerald-800' : 'bg-violet-100 text-violet-800' }}">
                                        {{ $user->role === 'admin' ? 'Beheerder' : 'Medewerker' }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-slate-600">{{ $user->department ?: '—' }}</td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $user->is_active ? 'Actief' : 'Inactief' }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-slate-500 whitespace-nowrap">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                                <td class="px-4 sm:px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-blue-600 hover:bg-blue-50 rounded-lg text-sm font-medium transition-colors">Bekijken</a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-slate-600 hover:bg-slate-50 rounded-lg text-sm font-medium transition-colors">Bewerken</a>
                                        @if($user->id !== auth()->id())
                                            <button type="button" onclick="deleteUser({{ $user->id }}, this.dataset.name)" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-red-600 hover:bg-red-50 rounded-lg text-sm font-medium transition-colors" data-name="{{ e($user->name) }}">Verwijderen</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 sm:px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                        </div>
                                        <h3 class="text-base font-semibold text-slate-900">Geen gebruikers</h3>
                                        <p class="mt-2 text-sm text-slate-500">Begin met het toevoegen van een gebruiker.</p>
                                        <a href="{{ route('admin.users.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                            Gebruiker toevoegen
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Empty state (filter) --}}
        <div id="empty-filter-state" class="bg-white rounded-xl shadow-sm border border-slate-100 p-8 sm:p-12 text-center" style="display: none;">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-900">Geen resultaten</h3>
            <p class="mt-2 text-sm text-slate-500 max-w-sm mx-auto">Er zijn geen gebruikers die voldoen aan je filters.</p>
            <button type="button" onclick="clearFilters()" class="mt-4 inline-flex items-center gap-2 px-4 py-2.5 text-blue-600 hover:bg-blue-50 rounded-xl text-sm font-medium transition-colors">Filters wissen</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const searchInput = document.getElementById('search-input');
    const roleFilter = document.getElementById('role-filter');
    const statusFilter = document.getElementById('status-filter');
    const refreshBtn = document.getElementById('refresh-btn');
    let searchTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterUsersClientSide, 300);
    });
    searchInput.addEventListener('keydown', e => { if (e.key === 'Enter') filterUsersClientSide(); });
    roleFilter.addEventListener('change', filterUsersClientSide);
    statusFilter.addEventListener('change', filterUsersClientSide);
    refreshBtn.addEventListener('click', () => location.reload());

    if (typeof UserAPI !== 'undefined') {
        try {
            const stats = await UserAPI.getUserStats();
            if (stats && typeof stats === 'object') {
                document.getElementById('total-users').textContent = stats.total_users ?? {{ $fallbackStats['total_users'] }};
                document.getElementById('admin-users').textContent = stats.admin_users ?? {{ $fallbackStats['admin_users'] }};
                document.getElementById('employee-users').textContent = stats.employee_users ?? {{ $fallbackStats['employee_users'] }};
                document.getElementById('active-users').textContent = stats.active_users ?? {{ $fallbackStats['active_users'] }};
            }
        } catch (_) {}
    }
});

function filterUsersClientSide() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase().trim();
    const roleVal = document.getElementById('role-filter').value;
    const statusVal = document.getElementById('status-filter').value;
    const rows = document.querySelectorAll('#users-tbody tr');
    const emptyFilter = document.getElementById('empty-filter-state');
    const tableWrap = document.getElementById('users-table');
    const isEmptyTable = rows.length === 1 && rows[0].querySelector('td[colspan]');
    let visibleCount = 0;

    rows.forEach(row => {
        if (row.querySelector('td[colspan]')) return;
        const nameEl = row.querySelector('td:first-child .text-slate-900');
        const emailEl = row.querySelector('td:first-child .text-slate-500');
        const roleSpan = row.querySelector('td:nth-child(2) span');
        const statusSpan = row.querySelector('td:nth-child(4) span');
        if (!nameEl || !emailEl || !roleSpan || !statusSpan) return;

        const name = nameEl.textContent.toLowerCase();
        const email = emailEl.textContent.toLowerCase();
        const role = roleSpan.textContent.toLowerCase();
        const status = statusSpan.textContent.toLowerCase();

        let show = true;
        if (searchTerm && !name.includes(searchTerm) && !email.includes(searchTerm)) show = false;
        if (roleVal && !role.includes(roleVal === 'admin' ? 'beheerder' : 'medewerker')) show = false;
        if (statusVal) {
            const wantActive = statusVal === 'true';
            const isActive = status.includes('actief') && !status.includes('inactief');
            if (wantActive !== isActive) show = false;
        }

        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });

    if (tableWrap) tableWrap.style.display = isEmptyTable || visibleCount > 0 ? 'block' : 'none';
    if (emptyFilter) emptyFilter.style.display = !isEmptyTable && rows.length > 0 && visibleCount === 0 ? 'block' : 'none';
}

function clearFilters() {
    document.getElementById('search-input').value = '';
    document.getElementById('role-filter').value = '';
    document.getElementById('status-filter').value = '';
    filterUsersClientSide();
}

async function deleteUser(userId, userName) {
    const msg = 'Weet je zeker dat je "' + (userName || 'deze gebruiker') + '" wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt.';
    if (!confirm(msg)) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ url('admin/users') }}/" + userId;
    form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
    document.body.appendChild(form);
    form.submit();
}

function formatDate(dateString) {
    const d = new Date(dateString);
    return d.toLocaleDateString('nl-NL', { year: 'numeric', month: 'short', day: 'numeric' });
}
</script>
@endsection
