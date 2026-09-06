<?php

namespace App\Models\Centre;

use App\Domain\Enums\CentreCode;
use App\Domain\Enums\CentreStatus;
use App\Domain\Enums\Locale;
use App\Models\Catalogue\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Centre extends Model
{
    protected $fillable = [
        'code',
        'name',
        'address',
        'landmark',
        'latitude',
        'longitude',
        'email',
        'postal_code',
        'status',
        'sort_order',
        'holiday_default_open',
        'seo_title',
        'seo_description',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'code' => CentreCode::class,
            'name' => 'array',
            'address' => 'array',
            'landmark' => 'array',
            'status' => CentreStatus::class,
            'holiday_default_open' => 'boolean',
            'seo_title' => 'array',
            'seo_description' => 'array',
        ];
    }

    public function phones(): HasMany
    {
        return $this->hasMany(CentrePhone::class)->orderBy('sort_order');
    }

    public function weeklyHours(): HasMany
    {
        return $this->hasMany(CentreWeeklyHours::class)->orderBy('weekday');
    }

    public function exceptions(): HasMany
    {
        return $this->hasMany(ScheduleException::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'centre_service');
    }

    public function scopeActive($query)
    {
        return $query->where('status', CentreStatus::Active);
    }

    public function isBookable(): bool
    {
        return $this->status === CentreStatus::Active;
    }

    public function translatedName(Locale $locale): string
    {
        return $this->name[$locale->value] ?? $this->name['fr'];
    }
}
