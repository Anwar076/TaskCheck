<!-- Footer -->
<footer class="relative overflow-hidden bg-white border-t border-slate-100">

    <div class="relative mx-auto max-w-7xl px-4 pb-8 pt-14 sm:px-6 lg:px-8">

        {{-- Main columns --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-6 gap-10 lg:gap-8 pb-10 border-b border-slate-100">

            {{-- Brand --}}
            <div class="sm:col-span-2 lg:col-span-2">
                <a href="{{ route('welcome') }}" class="inline-flex items-center mb-4">
                    <img src="{{ asset('logos/taskcheck-logo.png') }}"
                         alt="TaskCheck — Maak elke controle aantoonbaar"
                         width="640"
                         height="160"
                         loading="lazy"
                         decoding="async"
                         class="h-14 sm:h-16 w-auto max-w-full object-contain object-left">
                </a>
                <p class="text-sm text-slate-500 leading-relaxed max-w-xs">
                    Geef teams duidelijke taken en managers realtime overzicht. Minder discussie, meer grip.
                </p>
            </div>

            {{-- Pagina's --}}
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5">Pagina&rsquo;s</p>
                <ul class="space-y-3 text-sm">
                    @foreach([
                        ['Home',    route('welcome')],
                        ['Prijzen', route('pricing')],
                        ['Blog',    route('blog')],
                        ['Contact', route('contact')],
                    ] as [$label,$url])
                    <li><a href="{{ $url }}" class="text-slate-500 hover:text-blue-600 transition-colors">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Horeca oplossingen --}}
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5">Horeca oplossingen</p>
                <ul class="space-y-3 text-sm">
                    @foreach([
                        ['Horeca App', route('seo.horeca-app')],
                        ['Restaurant Checklist App', route('seo.restaurant-checklist-app')],
                        ['HACCP Formulieren', route('seo.haccp-formulieren')],
                        ['Temperatuurregistratie App', route('seo.temperatuurregistratie-app')],
                        ['Opening Checklist Horeca', route('seo.opening-checklist-horeca')],
                        ['Sluitingschecklist Horeca', route('seo.sluitings-checklist-horeca')],
                    ] as [$label,$url])
                    <li><a href="{{ $url }}" class="text-slate-500 hover:text-blue-600 transition-colors">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Schoonmaak oplossingen --}}
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5">Schoonmaak oplossingen</p>
                <ul class="space-y-3 text-sm">
                    @foreach([
                        ['App Schoonmaakbedrijf', route('seo.app-schoonmaakbedrijf')],
                        ['Schoonmaak Checklist', route('seo.schoonmaak-checklist')],
                        ['Werkcontrole App', route('seo.werkcontrole-app')],
                    ] as [$label,$url])
                    <li><a href="{{ $url }}" class="text-slate-500 hover:text-blue-600 transition-colors">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- CTA --}}
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5">Aan de slag</p>
                <p class="text-sm text-slate-500 mb-5 leading-relaxed">14 dagen gratis. Geen creditcard nodig.</p>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 w-full justify-center px-5 py-3 rounded-xl text-white font-bold text-sm transition-all hover:opacity-90"
                   style="background:linear-gradient(135deg,#2563eb,#6366f1);box-shadow:0 4px 16px rgba(37,99,235,.25)">
                    Start gratis
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
            <p class="text-center sm:text-left">&copy; {{ date('Y') }} TaskCheck. Alle rechten voorbehouden.</p>
            <p class="text-center sm:text-right">
                Gebouwd door
                <a href="https://brancom.nl" class="font-medium text-slate-500 underline decoration-slate-300 underline-offset-2 transition hover:text-blue-600 hover:decoration-blue-400" target="_blank" rel="noopener noreferrer">brancom.nl</a>
            </p>
        </div>
    </div>

    <script>
    (function () {
      let widgetLoaded = false;
      const loadWidget = () => {
        if (widgetLoaded) return;
        widgetLoaded = true;
        window.texviaConfig = {
          companyId: 'fe972c26-e4aa-4d76-9cee-06a37490fea8',
          theme: 'light',
          position: 'bottom-right'
        };
        const script = document.createElement('script');
        script.src = 'https://texvia-ai-support.lovable.app/widget.js';
        script.async = true;
        document.body.appendChild(script);
      };

      // Load after initial render to protect mobile LCP.
      if ('requestIdleCallback' in window) {
        requestIdleCallback(loadWidget, { timeout: 5000 });
      } else {
        setTimeout(loadWidget, 3000);
      }

      // Or sooner on first user interaction.
      ['pointerdown', 'keydown', 'touchstart'].forEach((eventName) => {
        window.addEventListener(eventName, loadWidget, { once: true, passive: true });
      });
    })();
    </script>
</footer>
