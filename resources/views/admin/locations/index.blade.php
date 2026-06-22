@extends('layouts.admin')

@section('page-title', 'Locaties')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <span class="text-slate-900 font-semibold truncate">Locaties</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        @php
            $locationLimit = $company?->getLocationLimit();
            $locationsWithAddress = $locations->filter(fn ($location) => filled($location->address))->values();
        @endphp

        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6.75-5.625-6.75-11.25a6.75 6.75 0 1113.5 0C18.75 15.375 12 21 12 21z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11.25a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Locaties</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">
                                    @if($company && $locationLimit === -1)
                                        Onbeperkt locaties op jouw plan.
                                    @elseif($company)
                                        {{ $company->getLocationCount() }} van {{ $locationLimit }} locaties in gebruik.
                                    @else
                                        Beheer je locaties en status.
                                    @endif
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.locations.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-700 text-sm font-semibold rounded-xl hover:bg-blue-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Locatie toevoegen
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 pb-6 pt-4 sm:px-8 sm:pb-8 sm:pt-5">
                @include('admin.settings.tabs', ['activeTab' => 'locations'])

                @if($locations->isNotEmpty())
                <div class="mb-6 overflow-hidden rounded-xl border border-slate-100 bg-slate-50">
                    <div class="flex flex-col gap-4 border-b border-slate-100 bg-white px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Locatiekaart</h2>
                            <p class="mt-1 text-sm text-slate-500">Bekijk waar je locaties zich bevinden.</p>
                        </div>
                        <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6.75-5.625-6.75-11.25a6.75 6.75 0 1113.5 0C18.75 15.375 12 21 12 21z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11.25a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                            </svg>
                            {{ $locationsWithAddress->count() }} met adres
                        </div>
                    </div>
                    <div class="grid gap-0 lg:grid-cols-[minmax(0,1fr)_18rem]">
                        <div class="relative h-[22rem] overflow-hidden bg-slate-100">
                            <div id="locations-map" class="absolute inset-0 h-full w-full"></div>
                            <div id="locations-map-state" class="absolute inset-0 flex items-center justify-center bg-slate-50/95 px-6 text-center">
                                <div>
                                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                        <svg class="h-6 w-6 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6.75-5.625-6.75-11.25a6.75 6.75 0 1113.5 0C18.75 15.375 12 21 12 21z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11.25a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-800">Kaart laden</p>
                                    <p class="mt-1 text-xs text-slate-500">Locaties worden op basis van adres geplaatst.</p>
                                </div>
                            </div>
                        </div>
                        <div class="border-t border-slate-100 bg-white p-4 lg:border-l lg:border-t-0">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Locaties op kaart</p>
                            <div id="locations-map-list" class="mt-3 space-y-2">
                                @forelse($locationsWithAddress as $location)
                                    <button
                                        type="button"
                                        class="w-full rounded-lg border border-slate-100 bg-slate-50 px-3 py-2 text-left transition hover:border-blue-200 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        data-location-map-item="{{ $location->id }}"
                                    >
                                        <p class="truncate text-sm font-semibold text-slate-900">{{ $location->name }}</p>
                                        <p class="mt-0.5 line-clamp-2 text-xs text-slate-500">{{ $location->address }}</p>
                                    </button>
                                @empty
                                    <div class="rounded-lg border border-dashed border-slate-200 px-3 py-4 text-sm text-slate-500">
                                        Voeg adressen toe om locaties op de kaart te tonen.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="rounded-xl sm:rounded-2xl border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Naam</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Adres</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Acties</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($locations as $location)
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ $location->name }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600">{{ $location->address ?: '-' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex rounded-lg px-2 py-1 text-xs font-medium {{ $location->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                                {{ $location->is_active ? 'Actief' : 'Gearchiveerd' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="inline-flex gap-2">
                                                <a href="{{ route('admin.locations.edit', $location) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">Bewerken</a>
                                                @if($location->is_active)
                                                    <form method="POST" action="{{ route('admin.locations.destroy', $location) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50" onclick="return confirm('Locatie archiveren?')">Archiveren</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500">Nog geen locaties toegevoegd.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    <style>
        #locations-map,
        #locations-map.leaflet-container {
            position: absolute;
            inset: 0;
            width: 100% !important;
            height: 100% !important;
        }

        #locations-map .leaflet-tile,
        #locations-map .leaflet-marker-icon,
        #locations-map .leaflet-marker-shadow,
        #locations-map .leaflet-pane,
        #locations-map .leaflet-tile-container,
        #locations-map .leaflet-layer {
            position: absolute;
        }
    </style>
@endpush

@push('scripts')
    @php
        $mapLocations = $locationsWithAddress
            ->map(fn ($location) => [
                'id' => $location->id,
                'name' => $location->name,
                'address' => $location->address,
                'is_active' => $location->is_active,
            ])
            ->values();
    @endphp

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        const initLocationsMap = async () => {
            const mapElement = document.getElementById('locations-map');
            const stateElement = document.getElementById('locations-map-state');
            const locations = @json($mapLocations);

            if (!mapElement || !stateElement) return;

            const setState = (title, text = '') => {
                stateElement.classList.remove('hidden');
                stateElement.innerHTML = `
                    <div>
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6.75-5.625-6.75-11.25a6.75 6.75 0 1113.5 0C18.75 15.375 12 21 12 21z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11.25a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-800">${title}</p>
                        ${text ? `<p class="mt-1 text-xs text-slate-500">${text}</p>` : ''}
                    </div>
                `;
            };

            if (!locations.length) {
                setState('Geen adressen gevonden', 'Vul eerst een adres in bij je locaties.');
                return;
            }

            if (!window.L) {
                setState('Kaart kon niet laden', 'Controleer je internetverbinding en probeer opnieuw.');
                return;
            }

            const escapeHtml = (value) => String(value || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');

            const cacheKey = 'taskcheck:location-geocode:v1';
            const geocodeCache = JSON.parse(localStorage.getItem(cacheKey) || '{}');
            const saveCache = () => localStorage.setItem(cacheKey, JSON.stringify(geocodeCache));

            const geocodeLocation = async (location) => {
                const cacheId = `${location.id}:${location.address}`;
                if (geocodeCache[cacheId]) return geocodeCache[cacheId];

                const url = new URL('https://nominatim.openstreetmap.org/search');
                url.searchParams.set('format', 'jsonv2');
                url.searchParams.set('limit', '1');
                url.searchParams.set('q', location.address);

                const response = await fetch(url.toString(), {
                    headers: { 'Accept': 'application/json' },
                });

                if (!response.ok) return null;

                const results = await response.json();
                const first = results[0];
                if (!first) return null;

                const coordinates = {
                    lat: Number(first.lat),
                    lng: Number(first.lon),
                };

                if (!Number.isFinite(coordinates.lat) || !Number.isFinite(coordinates.lng)) return null;

                geocodeCache[cacheId] = coordinates;
                saveCache();

                return coordinates;
            };

            const map = window.L.map(mapElement, {
                scrollWheelZoom: false,
            }).setView([52.1326, 5.2913], 7);

            window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap',
            }).addTo(map);

            const bounds = [];
            const markersByLocationId = {};
            let plotted = 0;

            for (const location of locations) {
                try {
                    const coordinates = await geocodeLocation(location);
                    if (!coordinates) continue;

                    bounds.push([coordinates.lat, coordinates.lng]);
                    plotted += 1;

                    const marker = window.L.marker([coordinates.lat, coordinates.lng]).addTo(map);
                    markersByLocationId[location.id] = {
                        marker,
                        coordinates,
                    };

                    marker.bindPopup(`
                        <div class="space-y-1">
                            <p class="font-semibold text-slate-900">${escapeHtml(location.name)}</p>
                            <p class="text-xs text-slate-600">${escapeHtml(location.address)}</p>
                        </div>
                    `);
                } catch (error) {
                    console.warn('Kon locatie niet op kaart plaatsen', location, error);
                }
            }

            if (!plotted) {
                setState('Geen locaties geplaatst', 'De adressen konden niet op de kaart worden gevonden.');
                return;
            }

            stateElement.classList.add('hidden');

            const fitMap = (resetBounds = true) => {
                map.invalidateSize();

                if (!resetBounds) return;

                if (bounds.length === 1) {
                    map.setView(bounds[0], 14);
                } else {
                    map.fitBounds(bounds, { padding: [32, 32], maxZoom: 14 });
                }
            };

            requestAnimationFrame(() => {
                fitMap();
                setTimeout(fitMap, 150);
                setTimeout(fitMap, 500);
            });

            if ('ResizeObserver' in window) {
                const observer = new ResizeObserver(() => fitMap(false));
                observer.observe(mapElement);
            }

            document.querySelectorAll('[data-location-map-item]').forEach((button) => {
                button.addEventListener('click', () => {
                    const item = markersByLocationId[button.dataset.locationMapItem];
                    if (!item) return;

                    document.querySelectorAll('[data-location-map-item]').forEach((otherButton) => {
                        otherButton.classList.toggle('border-blue-300', otherButton === button);
                        otherButton.classList.toggle('bg-blue-50', otherButton === button);
                    });

                    map.invalidateSize();
                    map.flyTo([item.coordinates.lat, item.coordinates.lng], 16, {
                        duration: 0.45,
                    });
                    item.marker.openPopup();
                });
            });
        };

        if (document.readyState === 'complete') {
            window.setTimeout(initLocationsMap, 0);
        } else {
            window.addEventListener('load', initLocationsMap, { once: true });
        }
    </script>
@endpush
