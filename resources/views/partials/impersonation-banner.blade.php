@if(session()->has('impersonator_id'))
    <div class="relative z-[60] flex flex-col items-center justify-between gap-2 border-b border-blue-800 bg-blue-700 px-4 py-2 text-sm text-white shadow-sm sm:flex-row">
        <p><strong>Meekijkmodus actief:</strong> je bent ingelogd als {{ auth()->user()->name }} ({{ auth()->user()->email }}). Handelingen worden uitgevoerd namens deze gebruiker.</p>
        <form method="POST" action="{{ route('impersonation.stop') }}" class="shrink-0">@csrf<button class="rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-blue-700 shadow-sm ring-1 ring-blue-200 hover:bg-blue-50">&larr; Terug naar superadmin</button></form>
    </div>
@endif
