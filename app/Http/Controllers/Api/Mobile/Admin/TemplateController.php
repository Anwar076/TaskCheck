<?php

namespace App\Http\Controllers\Api\Mobile\Admin;

use App\Http\Controllers\Api\Mobile\MobileController;
use App\Models\TaskTemplate;
use Illuminate\Http\Request;

class TemplateController extends MobileController
{
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;

        $templates = TaskTemplate::query()
            ->where(function ($q) use ($companyId) {
                $q->where('company_id', $companyId)
                    ->orWhereNull('company_id');
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'description' => $t->description,
                'is_active' => (bool) $t->is_active,
                'task_count' => $t->templateTasks()->count(),
            ])
            ->values();

        return $this->success($templates);
    }
}
