<!-- Footer -->
<footer class="relative overflow-hidden bg-white border-t border-slate-100">

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8 pt-14 pb-8">

        {{-- Main columns --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8 pb-10 border-b border-slate-100">

            {{-- Brand --}}
            <div class="sm:col-span-2 lg:col-span-1">
                <a href="{{ route('welcome') }}" class="inline-block mb-4">
                    <img src="{{ asset('logos/taskcheck-logo.svg') }}"
                         alt="TaskCheck logo"
                         width="320" height="96"
                         loading="lazy" decoding="async"
                         class="h-24 sm:h-28 w-auto">
                </a>
                <p class="text-sm text-slate-500 leading-relaxed max-w-xs">
                    Geef teams duidelijke taken en managers realtime overzicht. Minder discussie, meer grip.
                </p>
            </div>

            {{-- Pagina's --}}
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5">Pagina's</p>
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

            {{-- Branches --}}
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5">Branches</p>
                <ul class="space-y-3 text-sm">
                    @foreach([
                        ['Horeca',       route('seo.horeca-checklist-app')],
                        ['Schoonmaak',   route('seo.schoonmaak-checklist-app')],
                        ['Werkcontrole', route('seo.werkcontrole-app')],
                        ['Personeel',    route('seo.takenlijst-personeel')],
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
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
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