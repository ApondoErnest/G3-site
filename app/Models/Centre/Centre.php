<?php

namespace App\Models\Centre;

use App\Domain\Enums\CentreStatus;
use App\Domain\Enums\Locale;
use App\Models\Catalogue\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Centre extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'code',
        'name',
        'address',
        'landmark',
        'latitude',
        'longitude',
        'email',
        'secondary_email',
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
            'name' => 'array',
            'address' => 'array',
            'landmark' => 'array',
            'status' => CentreStatus::class,
            'holiday_default_open' => 'boolean',
            'seo_title' => 'array',
            'seo_description' => 'array',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('centres')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    /**
     * @return list<string>
     */
    public function contactEmails(): array
    {
        $emails = [$this->email];

        if (filled($this->secondary_email) && $this->secondary_email !== $this->email) {
            $emails[] = $this->secondary_email;
        }

        return $emails;
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
