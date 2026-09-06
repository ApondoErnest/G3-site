<?php

namespace App\Models\Content;

use App\Domain\Content\BilingualFields;
use Illuminate\Database\Eloquent\Model;

class RoadSafetySection extends Model
{
    protected $fillable = [
        'anchor',
        'title',
        'body',
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
            'body' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function isReadyToPublish(): bool
    {
        return BilingualFields::isComplete($this->title)
            && BilingualFields::isComplete($this->body);
    }
}
