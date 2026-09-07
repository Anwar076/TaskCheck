<?php

namespace App\Observers;

use App\Services\Platform\CompanyLogService;
use Illuminate\Database\Eloquent\Model;

class CompanyLogObserver
{
    public function created(Model $model): void
    {
        app(CompanyLogService::class)->record($model, 'created');
    }

    public function updated(Model $model): void
    {
        app(CompanyLogService::class)->record($model, 'updated');
    }

    public function deleted(Model $model): void
    {
        // Company logs disappear with the company; person/location history stays.
        if (! ($model instanceof \App\Models\Organisation\Company)) {
            app(CompanyLogService::class)->record($model, 'deleted');
        }
    }
}
