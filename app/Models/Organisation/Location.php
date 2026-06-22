<?php

namespace App\Models\Organisation;

use App\Models\Checklist\TaskList;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'name',
        'address',
        'street',
        'house_number',
        'postal_code',
        'city',
        'notes',
        'is_active',
        'company_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function taskLists()
    {
        return $this->hasMany(TaskList::class);
    }
}
