<?php

namespace App\Models\Content;

use App\Domain\Enums\ContentPage;
use App\Domain\Enums\Locale;
use Illuminate\Database\Eloquent\Model;

class PageSeo extends Model
{
    protected $table = 'page_seo';

    public $incrementing = false;

    public $timestamps = false;

    protected $primaryKey = 'page';

    protected $keyType = 'string';

    protected $fillable = [
        'page',
        'seo_title',
        'seo_description',
        'updated_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'page' => ContentPage::class,
            'seo_title' => 'array',
            'seo_description' => 'array',
            'updated_at' => 'datetime',
        ];
    }

    public function titleFor(Locale $locale): string
    {
        return $this->seo_title[$locale->value] ?? $this->seo_title['fr'] ?? '';
    }

    public function descriptionFor(Locale $locale): string
    {
        return $this->seo_description[$locale->value] ?? $this->seo_description['fr'] ?? '';
    }

    public function getKey(): string
    {
        $page = $this->getAttribute('page');

        if ($page instanceof ContentPage) {
            return $page->value;
        }

        return (string) $page;
    }

    public function getRouteKey(): string
    {
        return $this->getKey();
    }
}
