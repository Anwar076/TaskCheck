<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToCompany
{
    /**
     * Boot the trait
     */
    protected static function bootBelongsToCompany()
    {
        // Automatically set company_id when creating
        static::creating(function ($model) {
            try {
                if (auth()->check() && auth()->user() && auth()->user()->company_id && !$model->company_id) {
                    $model->company_id = auth()->user()->company_id;
                }
            } catch (\Exception $e) {
                // Silently fail if auth is not available (e.g., during migrations)
            }
        });

        // Global scope to filter by company (only when auth is available)
        static::addGlobalScope('company', function (Builder $builder) {
            try {
                if (auth()->check() && auth()->user() && auth()->user()->company_id) {
                    $builder->where($builder->getModel()->getTable() . '.company_id', auth()->user()->company_id);
                }
            } catch (\Exception $e) {
                // Silently fail if auth is not available (e.g., during migrations)
            }
        });
    }

    /**
     * Scope a query to only include records for the current company
     */
    public function scopeForCompany($query, $companyId = null)
    {
        $companyId = $companyId ?? (auth()->check() ? auth()->user()->company_id : null);
        
        if ($companyId) {
            return $query->where('company_id', $companyId);
        }
        
        return $query;
    }

    /**
     * Relationship to company
     */
    public function company()
    {
        return $this->belongsTo(\App\Models\Organisation\Company::class);
    }
}

