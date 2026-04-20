<!-- Footer -->
<footer class="relative mt-16 overflow-hidden border-t border-blue-100 bg-gradient-to-br from-white via-sky-50/60 to-indigo-50/60">
    <div class="pointer-events-none absolute -top-16 -left-12 w-52 h-52 rounded-full bg-cyan-200/45 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-16 right-0 w-60 h-60 rounded-full bg-fuchsia-200/40 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-6 py-12 relative">
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="rounded-2xl border border-blue-100 bg-white/90 p-6 shadow-sm">
                <a href="{{ route('welcome') }}" class="flex items-center gap-3 mb-4">
                    <img
                        src="{{ asset('logos/taskcheck-logo.svg') }}"
                        alt="TaskCheck logo"
                        class="h-24 sm:h-28 w-auto shrink-0"
                    >
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
                    <a href="{{ route('blog') }}" class="text-slate-600 hover:text-blue-700 transition">Blog</a>
                    <a href="{{ route('contact') }}" class="text-slate-600 hover:text-blue-700 transition">Contact</a>
                    <a href="{{ route('seo.horeca-checklist-app') }}" class="text-slate-600 hover:text-blue-700 transition">Horeca checklist app</a>
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