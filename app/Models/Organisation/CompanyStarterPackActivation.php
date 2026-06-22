<?php

namespace App\Models\Organisation;

use App\Models\Organisation\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyStarterPackActivation extends Model
{
    protected $fillable = [
        'company_id',
        'pack_slug',
        'activated_by',
        'templates_imported',
        'lists_created',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function activatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'activated_by');
    }
}
