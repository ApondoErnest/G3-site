<?php

namespace App\Models\Content;

use App\Domain\Enums\ContentPage;
use App\Domain\Enums\Locale;
use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
    protected $fillable = [
        'key',
        'page',
        'schema_version',
        'content',
        'locale_status',
        'is_published',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'page' => ContentPage::class,
            'content' => 'array',
            'locale_status' => 'array',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function isReadyToPublish(): bool
    {
        return ($this->locale_status['fr'] ?? null) === 'complete'
            && ($this->locale_status['en'] ?? null) === 'complete';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function contentFor(Locale $locale): ?array
    {
        return $this->content[$locale->value] ?? null;
    }
}
