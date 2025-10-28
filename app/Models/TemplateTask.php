<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateTask extends Model
{
    protected $fillable = [
        'template_id',
        'title',
        'description',
        'instructions',
        'required_proof_type',
        'is_required',
        'checklist_items',
        'sort_order',
        'is_active',
        'attachments',
        'validation_rules',
    ];

    protected $casts = [
        'checklist_items' => 'array',
        'is_active' => 'boolean',
        'is_required' => 'boolean',
        'attachments' => 'array',
        'validation_rules' => 'array',
    ];

    /**
     * Get the template this task belongs to
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(TaskTemplate::class, 'template_id');
    }
}
