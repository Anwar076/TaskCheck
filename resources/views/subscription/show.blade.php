@extends('layouts.admin')

@section('page-title', 'Abonnement')

@section('content')
<div class="py-6 sm:py-8 lg:py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 shadow-sm" role="alert">
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-emerald-800 font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Hero kaart --}}
            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-6 sm:px-8 py-8 sm:py-10">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 sm:w-9 sm:h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl font-bold text-white">{{ $company->name ?? 'Bedrijf' }}</h1>
                                <p class="text-blue-100 text-sm sm:text-base mt-1">Abonnementsdetails</p>
                            </div>
                        </div>
                        @if($company->subscription_status === 'trial')
                            <div class="flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur rounded-xl">
                                <span class="text-white font-semibold">{{ $company->trialDaysRemaining() }}</span>
                                <span class="text-blue-100 text-sm">dagen proefperiode over</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    <div class="grid md:grid-cols-2 gap-6 sm:gap-8">
                        {{-- Huidig plan --}}
                        <div class="space-y-5">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900">Huidig Plan</h3>
                            </div>

                            @if($company->subscription_status === 'trial')
                                <div class="space-y-3">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-xl text-sm font-semibold border border-blue-100">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Proefperiode
                                    </span>
                                    <div class="space-y-1 text-slate-600">
                                        <p class="flex justify-between"><span>Dagen resterend</span> <span class="font-semibold text-slate-900">{{ $company->trialDaysRemaining() }}</span></p>
                                        <p class="flex justify-between"><span>Proefperiode eindigt</span> <span class="font-semibold text-slate-900">{{ $company->trial_ends_at->format('d M Y') }}</span></p>
                                    </div>
                                </div>
                            @elseif($company->hasActiveSubscription())
                                <div class="space-y-3">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-semibold border border-emerald-100">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Actief abonnement
                                    </span>
                                    @if($planDetails)
                                        <p class="text-2xl font-bold text-blue-600">{{ $planDetails['name'] }}</p>
                                        <p class="text-slate-600">€{{ number_format($planDetails['price_monthly'], 2, ',', '.') }}/maand</p>
                                    @endif
                                    @if(!is_null($daysUntilNextBilling))
                                        <p class="text-slate-600">
                                            <span class="font-medium">Nieuwe facturatie over:</span>
                                            {{ $daysUntilNextBilling }} {{ $daysUntilNextBilling === 1 ? 'dag' : 'dagen' }}
                                        </p>
                                    @elseif(!is_null($nextBillingDate))
                                        <p class="text-slate-600">
                                            <span class="font-medium">Volgende facturatie:</span>
                                            {{ $nextBillingDate->format('d M Y') }}
                                        </p>
                                    @endif
                                    @if($company->subscription_ends_at)
                                        <p class="text-slate-600"><span class="font-medium">Verlengt op:</span> {{ $company->subscription_ends_at->format('d M Y') }}</p>
                                    @endif
                                    @if(!empty($pendingPlanDetails))
                                        <div class="rounded-xl border border-indigo-100 bg-indigo-50/70 px-4 py-3">
                                            <p class="text-sm font-semibold text-indigo-900">Volgende planwijziging</p>
                                            <p class="text-sm text-indigo-700 mt-1">
                                                Je plan wijzigt naar <strong>{{ $pendingPlanDetails['name'] }}</strong>
                                                @if(isset($nextBillingDate) && !is_null($nextBillingDate))
                                                    op {{ $nextBillingDate->format('d M Y') }}.
                                                @else
                                                    bij de volgende facturatie.
                                                @endif
                                            </p>
                                            <p class="text-xs text-indigo-700/80 mt-1">Je huidige plan blijft actief tot die datum. Er wordt nu niets dubbel afgerekend.</p>
                                        </div>
                                    @endif
                                </div>
                            @elseif($company->subscription_status === 'cancelled' && $company->subscription_ends_at && $company->subscription_ends_at->isFuture())
                                <div class="space-y-3">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-700 rounded-xl text-sm font-semibold border border-amber-100">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Opzegging bevestigd
                                    </span>
                                    @if($planDetails)
                                        <p class="text-2xl font-bold text-amber-600">{{ $planDetails['name'] }}</p>
                                    @endif
                                    <p class="text-slate-700">
                                        Je abonnement is <strong>succesvol opgezegd bij Mollie</strong>.
                                        Je toegang blijft actief tot <strong>{{ $company->subscription_ends_at->format('d M Y') }}</strong>.
                                    </p>
                                    <p class="text-sm text-slate-600">
                                        Daarna stopt de toegang automatisch en wordt er geen nieuwe factuur aangemaakt.
                                    </p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-700 rounded-xl text-sm font-semibold border border-amber-100">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ ucfirst($company->subscription_status) }}
                                    </span>
                                    <p class="text-slate-600">Kies een plan om verder te gaan.</p>
                                </div>
                            @endif

                            <a href="{{ route('subscription.choose-plan') }}"
                               class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                                </svg>
                                Plan Wijzigen
                            </a>
                        </div>

                        {{-- Gebruik --}}
                        <div class="space-y-5">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900">Gebruik</h3>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-slate-600 font-medium">Gebruikers</span>
                                        <span class="font-bold text-slate-900">
                                            {{ $company->getUserCount() }} / {{ $company->max_users == -1 ? '∞' : $company->max_users }}
                                        </span>
                                    </div>
                                    @if($company->max_users != -1)
                                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2.5 rounded-full transition-all duration-500"
                                                 style="width: {{ min(100, ($company->getUserCount() / $company->max_users) * 100) }}%"></div>
                                        </div>
                                    @else
                                        <div class="w-full bg-slate-100 rounded-full h-2.5">
                                            <div class="bg-gradient-to-r from-emerald-400 to-emerald-500 h-2.5 rounded-full" style="width: 100%"></div>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-slate-600 font-medium">Opslag</span>
                                        <span class="font-bold text-slate-900">
                                            @if($company->max_storage_gb == -1)
                                                {{ number_format($company->getStorageUsedGb(), 1, ',', '.') }} GB gebruikt (onbeperkt)
                                            @else
                                                {{ number_format($company->getStorageUsedGb(), 1, ',', '.') }} / {{ $company->max_storage_gb }} GB
                                            @endif
                                        </span>
                                    </div>
                                    @if($company->max_storage_gb != -1)
                                        @php
                                            $storageUsedGb = $company->getStorageUsedGb();
                                            $storagePercent = $company->max_storage_gb > 0 ? min(100, ($storageUsedGb / $company->max_storage_gb) * 100) : 0;
                                        @endphp
                                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                            <div class="h-2.5 rounded-full transition-all duration-500 {{ $storagePercent >= 90 ? 'bg-red-500' : ($storagePercent >= 70 ? 'bg-amber-500' : 'bg-gradient-to-r from-blue-500 to-blue-600') }}"
                                                 style="width: {{ $storagePercent }}%"></div>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    @php $locationLimit = $company->getLocationLimit(); @endphp
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-slate-600 font-medium">Locaties</span>
                                        <span class="font-bold text-slate-900">
                                            {{ $company->getLocationCount() }} / {{ $locationLimit == -1 ? '∞' : $locationLimit }}
                                        </span>
                                    </div>
                                    @if($locationLimit != -1)
                                        @php
                                            $locationPercent = $locationLimit > 0 ? min(100, ($company->getLocationCount() / $locationLimit) * 100) : 0;
                                        @endphp
                                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2.5 rounded-full transition-all duration-500"
                                                 style="width: {{ $locationPercent }}%"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Gevarenzone --}}
                    @if($company->hasActiveSubscription())
                        <div class="mt-8 pt-8 border-t border-slate-200">
                            <div class="flex items-start gap-3 p-6 bg-red-50/50 rounded-2xl border border-red-100">
                                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-red-800 mb-1">Abonnement Opzeggen</h3>
                                    <p class="text-red-700/90 text-sm mb-4">Opzeggen betekent dat je na de huidige periode geen toegang meer hebt tot TaskCheck.</p>
                                    <form action="{{ route('subscription.cancel') }}" method="POST" onsubmit="return confirm('Weet je zeker dat je je abonnement wilt opzeggen? Je kunt tot het einde van de betaalperiode blijven gebruiken.');">
                                        @csrf
                                        <button type="submit"
                                                class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold transition-colors shadow-sm">
                                            Abonnement Opzeggen
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @elseif($company->subscription_status === 'cancelled')
                        <div class="mt-8 pt-8 border-t border-slate-200">
                            <div class="flex items-start gap-3 p-6 bg-amber-50/50 rounded-2xl border border-amber-100">
                                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-amber-800 mb-1">Abonnement is opgezegd</h3>
                                    @if($company->subscription_ends_at && $company->subscription_ends_at->isFuture())
                                        <p class="text-amber-800/90 text-sm">
                                            De opzegging is bevestigd. Je houdt toegang tot
                                            <strong>{{ $company->subscription_ends_at->format('d M Y') }}</strong>.
                                        </p>
                                    @else
                                        <p class="text-amber-800/90 text-sm">
                                            De opzegging is bevestigd en je abonnement is beëindigd.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
