<?php

namespace App\Models\Marketing;

use App\Models\Organisation\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MarketingLinkCampaign extends Model
{
    protected $fillable = [
        'code',
        'name',
        'destination_url',
        'created_by',
        'is_active',
        'clicks_count',
        'unique_clicks_count',
        'last_clicked_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_clicked_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(MarketingLinkClick::class);
    }

    public function getTrackingUrlAttribute(): string
    {
        return route('marketing-link.redirect', ['code' => $this->code]);
    }

    /** Zichtbare linktekst in mails (bijv. taskcheck.nl). */
    public function getMailLinkTextAttribute(): string
    {
        $host = parse_url($this->destination_url, PHP_URL_HOST);

        return is_string($host) && $host !== '' ? $host : 'taskcheck.nl';
    }

    /** HTML voor mail: zichtbaar taskcheck.nl, href = track-URL. */
    public function getMailLinkHtmlAttribute(): string
    {
        return '<a href="'.e($this->tracking_url).'">'.e($this->mail_link_text).'</a>';
    }

    public static function generateUniqueCode(string $base): string
    {
        $slug = Str::slug($base);
        if ($slug === '') {
            $slug = 'link';
        }

        $candidate = Str::limit($slug, 40, '');
        $suffix = 0;

        while (static::query()->where('code', $candidate)->exists()) {
            $suffix++;
            $candidate = Str::limit($slug, 36, '') . '-' . $suffix;
        }

        return $candidate;
    }

    public function recordClick(Request $request): void
    {
        $ip = (string) $request->ip();
        $userAgent = Str::limit((string) $request->userAgent(), 500, '');
        $visitorHash = hash('sha256', $ip . '|' . $userAgent . '|' . $this->id);

        $isUnique = !$this->clicks()
            ->where('visitor_hash', $visitorHash)
            ->exists();

        $this->clicks()->create([
            'visitor_hash' => $visitorHash,
            'user_agent' => $userAgent !== '' ? $userAgent : null,
            'referer' => Str::limit((string) $request->headers->get('referer'), 2000, ''),
            'clicked_at' => now(),
        ]);

        $this->increment('clicks_count');
        if ($isUnique) {
            $this->increment('unique_clicks_count');
        }

        $this->forceFill(['last_clicked_at' => now()])->save();
    }
}
