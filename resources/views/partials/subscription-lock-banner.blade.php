@if(!empty($subscriptionLocked) && !empty($subscriptionLockMessage))
    <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-900">
        <p class="font-semibold">Toegang beperkt</p>
        <p class="mt-1">{{ $subscriptionLockMessage }}</p>
        @if(auth()->user()?->isAdmin() && !auth()->user()?->isSuperAdmin() && auth()->user()?->company?->is_active)
            @php $lockedCompany = auth()->user()->company; @endphp
            @if($lockedCompany->needsFirstPayment())
                <form method="POST" action="{{ url('/subscription/activate') }}" class="mt-3">
                    @csrf
                    <input type="hidden" name="plan" value="{{ $lockedCompany->subscription_plan }}">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-amber-600 px-3 py-2 text-xs font-semibold text-white hover:bg-amber-700">
                        Betaal je abonnement nu
                    </button>
                </form>
            @else
                <a href="{{ route('subscription.choose-plan') }}" class="mt-3 inline-flex items-center rounded-lg bg-amber-600 px-3 py-2 text-xs font-semibold text-white hover:bg-amber-700">
                    Abonnement kiezen
                </a>
            @endif
        @endif
    </div>
@endif
