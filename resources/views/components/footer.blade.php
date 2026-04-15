<!-- Footer -->
<footer class="relative mt-16 overflow-hidden border-t border-blue-100 bg-gradient-to-br from-white via-sky-50/60 to-indigo-50/60">
    <div class="pointer-events-none absolute -top-16 -left-12 w-52 h-52 rounded-full bg-cyan-200/45 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-16 right-0 w-60 h-60 rounded-full bg-fuchsia-200/40 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-6 py-12 relative">
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="rounded-2xl border border-blue-100 bg-white/90 p-6 shadow-sm">
                <a href="{{ route('welcome') }}" class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-extrabold text-slate-900">TaskCheck</p>
                        <p class="text-xs text-slate-500">Slimme checklists voor operationele teams</p>
                    </div>
                </a>
                <p class="text-sm text-slate-600">
                    Minder ruis, meer grip: geef teams duidelijke taken en managers realtime overzicht.
                </p>
            </div>

            <div class="rounded-2xl border border-indigo-100 bg-white/90 p-6 shadow-sm">
                <h3 class="font-semibold text-slate-900 mb-3">Pagina's</h3>
                <div class="flex flex-col gap-2 text-sm">
                    <a href="{{ route('welcome') }}" class="text-slate-600 hover:text-blue-700 transition">Home</a>
                    <a href="{{ route('pricing') }}" class="text-slate-600 hover:text-blue-700 transition">Prijzen</a>
                    <a href="{{ route('contact') }}" class="text-slate-600 hover:text-blue-700 transition">Contact</a>
                </div>
            </div>

            <div class="rounded-2xl border border-emerald-100 bg-white/90 p-6 shadow-sm">
                <h3 class="font-semibold text-slate-900 mb-3">Start vandaag</h3>
                <p class="text-sm text-slate-600 mb-4">Probeer TaskCheck 30 dagen gratis en bekijk direct wat het voor je team oplevert.</p>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center w-full rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold px-4 py-2.5 transition">
                    Probeer 30 dagen gratis
                </a>
            </div>
        </div>

        <div class="mt-8 pt-5 border-t border-blue-100 flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-slate-500">
            <p>© {{ date('Y') }} TaskCheck. Alle rechten voorbehouden.</p>
            <p>Gebouwd in Nederland met Laravel & Tailwind CSS.</p>
        </div>
    </div>
    <script>
  window.texviaConfig = {
    companyId: 'fe972c26-e4aa-4d76-9cee-06a37490fea8',
    theme: 'light',
    position: 'bottom-right'
  };
</script>
<script src="https://texvia-ai-support.lovable.app/widget.js"></script>
</footer>