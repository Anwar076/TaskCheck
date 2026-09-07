<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organisation\Company;
use App\Models\Platform\CompanyLogEntry;
use App\Services\Platform\CompanyLogService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyLogController extends Controller
{
    public function store(Request $request, Company $company, CompanyLogService $log): RedirectResponse
    {
        $data = $request->validate([
            'category' => ['required', Rule::in(['note', 'email', 'call'])],
            'title' => ['nullable', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:20000'],
            'occurred_at' => ['nullable', 'date', 'before_or_equal:now'],
        ]);
        CompanyLogEntry::create(array_merge($log->actor(), [
            'company_id' => $company->id,
            'category' => $data['category'],
            'title' => $data['title'] ?? match ($data['category']) {
                'email' => 'E-mail vastgelegd', 'call' => 'Telefoongesprek', default => 'Notitie',
            },
            'body' => $data['body'],
            'occurred_at' => isset($data['occurred_at']) ? Carbon::parse($data['occurred_at'], config('app.timezone')) : now(),
        ]));

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'logbook'])
            ->with('success', 'Toegevoegd aan het logboek.');
    }
}
