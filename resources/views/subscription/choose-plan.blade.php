@extends('layouts.admin')

@section('page-title', 'Plan Kiezen')

@section('content')
@php
    $planFeatures = [
        'starter' => [
            '1 admin account',
            '5 medewerker accounts',
            '1 locatie',
            'Taken met foto- en videobewijs',
            'Realtime voortgangsoverzicht',
            'Mobiele webapp (installeerbaar)',
        ],
        'professional' => [
            '2 admin accounts',
            '10 medewerker accounts',
            '2 locaties',
            'AI-import (PDF, Excel, Word of foto)',
            'Weekoverzicht & rapportages',
            'Taken met foto- en videobewijs',
            'Realtime voortgangsoverzicht',
            'Mobiele webapp (installeerbaar)',
            'Priority support',
        ],
        'business' => [
            '5 admin accounts',
            '20 medewerker accounts',
            '3 locaties',
            'Uitgebreide rapportages per locatie',
            'Inzicht in prestaties per team en locatie',
            'Taken met foto- en videobewijs',
            'Realtime voortgangsoverzicht',
            'Mobiele webapp (installeerbaar)',
            'Priority support',
        ],
    ];
@endphp
<div class="py-6 sm:py-8 lg:py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
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

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3 shadow-sm" role="alert">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-red-800 font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-center gap-3 shadow-sm" role="alert">
                    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <span class="text-amber-800 font-medium">{{ session('warning') }}</span>
                </div>
            @endif

            {{-- Hero sectie --}}
            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-6 sm:px-8 py-8 sm:py-10">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 sm:w-9 sm:h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl font-bold text-white">Kies Je Abonnement</h1>
                                <p class="text-blue-100 text-sm sm:text-base mt-1">Kies het plan dat past bij jouw organisatie en start direct.</p>
                            </div>
                        </div>
                        @if($company)
                            <a href="{{ route('subscription.show') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white/20 hover:bg-white/30 backdrop-blur rounded-xl text-white font-semibold transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Terug naar overzicht
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Trial banners --}}
                <div class="p-6 sm:p-8">
                    @if($company && $company->isOnTrial())
                        <div class="mb-8 p-5 bg-blue-50 border border-blue-100 rounded-2xl flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-blue-900">Proefperiode Actief</h3>
                                <p class="text-blue-700 text-sm mt-1">Je hebt nog {{ $trialDaysRemaining }} {{ $trialDaysRemaining === 1 ? 'dag' : 'dagen' }} in je gratis proefperiode. Kies een plan om na je proefperiode door te gaan.</p>
                            </div>
                        </div>
                    @endif

                    @if($trialExpired ?? false)
                        <div class="mb-8 p-5 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-4">
                            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-red-900">Proefperiode Verlopen</h3>
                                <p class="text-red-700 text-sm mt-1">Je 14-dagen gratis proefperiode is afgelopen. Kies een abonnementsplan om de service te blijven gebruiken.</p>
                            </div>
                        </div>
                    @endif

                    @if($company && $company->hasActiveSubscription() && $company->pending_subscription_plan)
                        <div class="mb-8 p-5 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-start gap-4">
                            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 12h8m-8 5h5"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-indigo-900">Planwijziging ingepland</h3>
                                <p class="text-indigo-700 text-sm mt-1">Je nieuwe plan ({{ ucfirst($company->pending_subscription_plan) }}) gaat in bij de volgende facturatie. Tot die tijd blijft je huidige plan actief.</p>
                            </div>
                        </div>
                    @endif

                    {{-- Plan kaarten --}}
                    <div class="grid md:grid-cols-3 gap-6 sm:gap-8">
                        @foreach($plans as $planKey => $plan)
                            @if(in_array($planKey, ['starter', 'professional', 'business'], true))
                                <div class="relative rounded-2xl border-2 p-6 sm:p-8 flex flex-col transition-all duration-300 {{ $planKey === 'professional' ? 'border-blue-500 bg-gradient-to-b from-blue-50/50 to-white shadow-xl md:scale-105 z-10' : 'border-slate-200 bg-white hover:border-slate-300 hover:shadow-lg' }}">
                                    @if($planKey === 'professional')
                                        <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                                            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-600 text-white rounded-full text-xs font-bold shadow-md">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                Meest Populair
                                            </span>
                                        </div>
                                    @endif

                                    <div class="text-center mb-6">
                                        <h4 class="text-xl font-bold text-slate-900">{{ $plan['name'] }}</h4>
                                        <div class="mt-4">
                                            <span class="text-3xl sm:text-4xl font-bold {{ $planKey === 'professional' ? 'text-blue-600' : 'text-slate-900' }}">€{{ number_format($plan['price_monthly'], 0, ',', '.') }}</span>
                                            <span class="text-slate-500 font-medium">/maand</span>
                                        </div>
                                    </div>

                                    <ul class="space-y-4 mb-8 flex-1">
                                        @foreach($planFeatures[$planKey] ?? [] as $feature)
                                            <li class="flex items-center gap-3">
                                                <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <span class="text-slate-600">{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <form action="{{ route('subscription.activate') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="plan" value="{{ $planKey }}">
                                        <button type="submit"
                                                class="w-full py-3.5 px-4 rounded-xl font-semibold transition-all shadow-sm {{ $currentPlan === $planKey && !$company?->pending_subscription_plan ? 'bg-slate-100 text-slate-500 cursor-default' : ($planKey === 'professional' ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-blue-200' : 'bg-slate-800 hover:bg-slate-900 text-white') }}">
                                            @if($currentPlan === $planKey && !$company?->pending_subscription_plan)
                                                Huidig plan
                                            @elseif($company?->hasActiveSubscription() && $company?->pending_subscription_plan === $planKey)
                                                Ingepland voor volgende maand
                                            @else
                                                Kies {{ $plan['name'] }}
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="mt-10 pt-8 border-t border-slate-200 text-center">
                        <p class="text-slate-600 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Alle plannen bevatten 14 dagen gratis proefperiode. Betaling verloopt veilig via Mollie.
                        </p>
                        <p class="mt-2 text-sm text-slate-500">
                            Er wordt bij het afrekenen 21% btw in rekening gebracht, het standaardtarief in Nederland.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
