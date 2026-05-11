@extends('layouts.super-admin')

@section('page-title', 'Global Templates')

@section('content')
<div class="space-y-4">
    @if(session('success'))
        <div class="rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h1 class="text-xl font-bold text-slate-900">Global templates</h1>
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('super-admin.templates.index') }}">
                <select name="company_type" onchange="this.form.submit()" class="rounded-lg border-slate-300 text-sm">
                    <option value="all" @selected(($filterType ?? 'all') === 'all')>Alles</option>
                    <option value="cleaning" @selected(($filterType ?? 'all') === 'cleaning')>Schoonmaak</option>
                    <option value="horeca" @selected(($filterType ?? 'all') === 'horeca')>Horeca</option>
                    <option value="other" @selected(($filterType ?? 'all') === 'other')>Anders</option>
                </select>
            </form>
            <a href="{{ route('super-admin.templates.ai-import') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-violet-100 text-violet-800 border border-violet-200 px-4 py-2 text-sm font-semibold hover:bg-violet-200">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                AI importeren
            </a>
            <a href="{{ route('super-admin.templates.create') }}" class="rounded-lg bg-violet-700 text-white px-4 py-2 text-sm font-semibold hover:bg-violet-800">Template toevoegen</a>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50">
                <tr class="text-left text-slate-500">
                    <th class="px-4 py-3">Template</th>
                    <th class="px-4 py-3">Doelgroep</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Taken</th>
                    <th class="px-4 py-3">Laatst bijgewerkt</th>
                    <th class="px-4 py-3 text-right">Acties</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $template)
                    @php
                        $lastPublishedAt = $template->source_updated_at;
                        $isPublished = !is_null($lastPublishedAt);
                        $hasUnpublishedChanges = $isPublished && $template->updated_at && $template->updated_at->gt($lastPublishedAt);
                        $statusLabel = 'Concept';
                        $statusClass = 'bg-amber-100 text-amber-700';
                        $publishLabel = 'Publiceren';
                        $publishDisabled = false;

                        if ($isPublished && !$hasUnpublishedChanges) {
                            $statusLabel = 'Gepubliceerd';
                            $statusClass = 'bg-emerald-100 text-emerald-700';
                            $publishLabel = 'Gepubliceerd';
                            $publishDisabled = true;
                        } elseif ($isPublished && $hasUnpublishedChanges) {
                            $statusLabel = 'Concept update klaar';
                            $statusClass = 'bg-blue-100 text-blue-700';
                            $publishLabel = 'Update publiceren';
                        }
                    @endphp
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-900">{{ $template->name }}</p>
                            <p class="text-xs text-slate-500">{{ $template->description }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @if(!$template->target_company_type)
                                Alle bedrijven
                            @elseif($template->target_company_type === 'cleaning')
                                Schoonmaak
                            @elseif($template->target_company_type === 'horeca')
                                Horeca
                            @elseif($template->target_company_type === 'other')
                                Anders
                            @else
                                {{ ucfirst($template->target_company_type) }}
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $template->templateTasks->count() }}</td>
                        <td class="px-4 py-3">{{ optional($template->updated_at)->format('d-m-Y H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('super-admin.templates.edit', $template) }}" class="rounded border border-slate-300 px-3 py-1.5 text-xs hover:bg-slate-50">Bewerken</a>
                                <form method="POST" action="{{ route('super-admin.templates.publish', $template) }}">
                                    @csrf
                                    <button
                                        @disabled($publishDisabled)
                                        class="rounded px-3 py-1.5 text-xs text-white {{ $publishDisabled ? 'bg-slate-400 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700' }}"
                                    >
                                        {{ $publishLabel }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('super-admin.templates.destroy', $template) }}"
                                      onsubmit="return confirm('Weet je zeker dat je \'{{ addslashes($template->name) }}\' wilt verwijderen? Dit kan niet ongedaan worden gemaakt.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded border border-red-200 px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 hover:border-red-300">
                                        Verwijderen
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">Nog geen global templates.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

