<?php

namespace App\Models\Platform;

use Illuminate\Database\Eloquent\Model;

class CompanyLogEntry extends Model
{
    protected $guarded = [];

    protected $casts = ['changes' => 'array', 'occurred_at' => 'datetime'];

    public const CATEGORIES = [
        'company' => 'Bedrijfswijzigingen',
        'user' => 'Gebruikers',
        'location' => 'Locaties',
        'note' => 'Notities',
        'email' => 'E-mails',
        'call' => 'Telefoongesprekken',
    ];
}
