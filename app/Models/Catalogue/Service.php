<?php

namespace App\Models\Catalogue;

use App\Domain\Enums\Locale;
use App\Models\Centre\Centre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'code',
        'title',
        'summary',
        'body',
        'icon',
        'sort_order',
        'is_published',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'title' => 'array',
            'summary' => 'array',
            'body' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function centres(): BelongsToMany
    {
        return $this->belongsToMany(Centre::class, 'centre_service');
    }

    public function vehicleCategories(): BelongsToMany
    {
        return $this->belongsToMany(VehicleCategory::class, 'service_vehicle_category');
    }

    public function requiredDocuments(): HasMany
    {
        return $this->hasMany(RequiredDocument::class)->orderBy('sort_order');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function translatedTitle(Locale $locale): string
    {
        $title = $this->title ?? [];

        return $title[$locale->value] ?? $title['fr'] ?? $title['en'] ?? $this->code;
    }

    public function isAvailableAt(Centre $centre, VehicleCategory $category): bool
    {
        if (! $this->is_published || ! $category->is_published) {
            return false;
        }

        if (! $this->centres->contains('id', $centre->id)) {
            return false;
        }

        return $this->vehicleCategories->contains('id', $category->id);
    }
}
