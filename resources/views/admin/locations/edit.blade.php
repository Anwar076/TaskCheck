@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-3xl">
    <h1 class="text-2xl font-bold text-slate-900 mb-6">Locatie bewerken</h1>

    <form method="POST" action="{{ route('admin.locations.update', $location) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Naam locatie</label>
            <input id="name" name="name" type="text" value="{{ old('name', $location->name) }}" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm">
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="address" class="mb-1.5 block text-sm font-medium text-slate-700">Adres</label>
            <input id="address" name="address" type="text" value="{{ old('address', $location->address) }}" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm">
            @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="notes" class="mb-1.5 block text-sm font-medium text-slate-700">Notities</label>
            <textarea id="notes" name="notes" rows="4" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm">{{ old('notes', $location->notes) }}</textarea>
            @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $location->is_active) ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600">
            Actief
        </label>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.locations.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuleren</a>
            <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Opslaan</button>
        </div>
    </form>
</div>
@endsection
