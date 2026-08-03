@extends('layouts.super-admin')

@section('page-title', 'Global Templates')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h1 class="text-xl font-bold text-slate-900">Global templates</h1>
        <div class="flex flex-wrap items-center gap-2">
            <form method="GET" action="{{ route('super-admin.templates.index') }}">
                <select name="company_type" onchange="this.form.submit()" class="rounded-lg border-slate-300 text-sm">
                    <option value="all" @selected(($filterType ?? 'all') === 'all')>Alles</option>
                    <option value="cleaning" @selected(($filterType ?? 'all') === 'cleaning')>Schoonmaak</option>
                    <option value="horeca" @selected(($filterType ?? 'all') === 'horeca')>Horeca</option>
                    <option value="other" @selected(($filterType ?? 'all') === 'other')>Anders</option>
                </select>
            </form>
            <a href="{{ route('super-admin.templates.ai-import') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-100 text-blue-800 border border-blue-200 px-4 py-2 text-sm font-semibold hover:bg-blue-200">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                AI importeren
            </a>
            <a href="{{ route('super-admin.templates.create') }}" class="rounded-lg bg-blue-600 text-white px-4 py-2 text-sm font-semibold hover:bg-blue-700">Template toevoegen</a>
        </div>
    </div>

    <div class="space-y-3 md:hidden">
        @forelse($templates as $template)
            @php
                $mobilePublished = !is_null($template->source_updated_at);
                $mobileChanged = $mobilePublished && $template->updated_at && $template->updated_at->gt($template->source_updated_at);
                $mobileStatus = !$mobilePublished ? 'Concept' : ($mobileChanged ? 'Update klaar' : 'Gepubliceerd');
                $mobileStatusClass = !$mobilePublished ? 'bg-amber-100 text-amber-700' : ($mobileChanged ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700');
            @endphp
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <h2 class="font-semibold text-slate-900">{{ $template->name }}</h2>
                        <p class="mt-1 line-clamp-2 text-xs text-slate-500">{{ $template->description ?: 'Geen beschrijving.' }}</p>
                    </div>
                    <span class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $mobileStatusClass }}">{{ $mobileStatus }}</span>
                </div>
                <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-600">
                    <span class="rounded-lg bg-slate-100 px-2 py-1">{{ $template->category ?: 'Geen categorie' }}</span>
                    <span class="rounded-lg bg-slate-100 px-2 py-1">{{ $template->templateTasks->count() }} taken</span>
                    <span class="rounded-lg bg-slate-100 px-2 py-1">{{ $template->frequency_label ?: 'Geen frequentie' }}</span>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <a href="{{ route('super-admin.templates.edit', $template) }}" class="flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Bewerken</a>
                    <form method="POST" action="{{ route('super-admin.templates.publish', $template) }}">@csrf<button class="w-full rounded-xl bg-emerald-600 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-700" @disabled($mobilePublished && !$mobileChanged)>{{ $mobilePublished && !$mobileChanged ? 'Gepubliceerd' : 'Publiceren' }}</button></form>
                    <form method="POST" action="{{ route('super-admin.templates.destroy', $template) }}" class="col-span-2" onsubmit="return confirm('Weet je zeker dat je dit template wilt verwijderen?');">@csrf @method('DELETE')<button class="w-full rounded-xl px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">Template verwijderen</button></form>
                </div>
            </article>
        @empty
            <p class="rounded-xl border border-dashed border-slate-200 p-8 text-center text-sm text-slate-500">Nog geen global templates.</p>
        @endforelse
    </div>

    <div class="hidden overflow-x-auto bg-white border border-slate-200 rounded-xl md:block">
        <table class="min-w-[980px] w-full text-sm">
            <thead class="bg-slate-50">
                <tr class="text-left text-slate-500">
                    <th class="px-4 py-3">Template</th>
                    <th class="px-4 py-3">Categorie</th>
                    <th class="px-4 py-3">Frequentie</th>
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
                            <p class="font-semibold text-slate-900">
                                @if(!empty($template->icon))
                                    <span class="text-slate-500">{{ $template->icon }}</span> ·
                                @endif
                                {{ $template->name }}
                            </p>
                            <p class="text-xs text-slate-500">{{ $template->description }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $template->category ?: '—' }}</td>
                        <td class="px-4 py-3">{{ $template->frequency_label ?: '—' }}</td>
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
                        <td colspan="8" class="px-4 py-8 text-center text-slate-500">Nog geen global templates.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
