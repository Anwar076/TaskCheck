@if(session()->has('impersonator_id'))
    <div class="relative z-[60] flex flex-col items-center justify-between gap-2 bg-amber-300 px-4 py-2 text-sm text-amber-950 shadow-sm sm:flex-row">
        <p><strong>Meekijkmodus actief:</strong> je bent ingelogd als {{ auth()->user()->name }} ({{ auth()->user()->email }}). Handelingen worden uitgevoerd namens deze gebruiker.</p>
        <form method="POST" action="{{ route('impersonation.stop') }}" class="shrink-0">@csrf<button class="rounded-lg bg-amber-950 px-3 py-1.5 text-xs font-semibold text-white hover:bg-black">Terug naar superadmin</button></form>
    </div>
@endif
