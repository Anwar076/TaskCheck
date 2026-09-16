@php
    $reportsTab = $reportsTab ?? 'overview';
@endphp
<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('admin.weekly-overview') }}"
       class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $reportsTab === 'overview' ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/20' : 'bg-white text-slate-600 border border-slate-200 hover:border-blue-200 hover:text-blue-700' }}">
        Overzicht
    </a>
    <a href="{{ route('admin.reports.dossier') }}"
       class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $reportsTab === 'dossier' ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/20' : 'bg-white text-slate-600 border border-slate-200 hover:border-blue-200 hover:text-blue-700' }}">
        Dossier
    </a>
</div>
