<?php

namespace App\Models\Catalogue;

use App\Domain\Enums\Locale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleCategory extends Model
{
    protected $fillable = [
        'code',
        'label',
        'examples',
        'description',
        'sort_order',
        'is_published',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'label' => 'array',
            'examples' => 'array',
            'description' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_vehicle_category');
    }

    public function requiredDocuments(): HasMany
    {
        return $this->hasMany(RequiredDocument::class)->orderBy('sort_order');
    }

    public function translatedLabel(Locale $locale): string
    {
        $label = $this->label ?? [];

        return $label[$locale->value] ?? $label['fr'] ?? $label['en'] ?? $this->code;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
