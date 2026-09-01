<?php

namespace App\Models\Organisation;

use Illuminate\Database\Eloquent\Model;

class CompanyReportRecipient extends Model
{
    public const SECTION_SUMMARY = 'summary';

    public const SECTION_TOP_LISTS = 'top_lists';

    public const SECTION_EMPLOYEE_PERFORMANCE = 'employee_performance';

    public const DEFAULT_SECTIONS = [
        self::SECTION_SUMMARY => true,
        self::SECTION_TOP_LISTS => true,
        self::SECTION_EMPLOYEE_PERFORMANCE => true,
    ];

    protected $fillable = ['email', 'frequency', 'send_time', 'weekly_day', 'delivery_format', 'sections', 'is_enabled', 'last_sent_at'];

    protected $casts = ['sections' => 'array', 'is_enabled' => 'boolean', 'last_sent_at' => 'datetime'];

    /** @return array<string, bool> */
    public function normalizedSections(): array
    {
        return self::normalizeSections($this->sections);
    }

    /** @return array<string, bool> */
    public static function normalizeSections(?array $sections): array
    {
        return collect(self::DEFAULT_SECTIONS)
            ->mapWithKeys(fn (bool $default, string $section) => [
                $section => array_key_exists($section, $sections ?? [])
                    ? (bool) $sections[$section]
                    : $default,
            ])
            ->all();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
