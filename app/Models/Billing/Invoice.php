<?php

namespace App\Models\Billing;

use App\Models\Organisation\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'company_id',
        'payment_id',
        'invoice_number',
        'description',
        'currency',
        'amount',
        'vat_rate',
        'amount_ex_vat',
        'vat_amount',
        'paid_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'vat_rate' => 'decimal:2',
            'amount_ex_vat' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
