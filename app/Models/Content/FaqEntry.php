<?php

namespace App\Models\Content;

use App\Domain\Content\BilingualFields;
use Illuminate\Database\Eloquent\Model;

class FaqEntry extends Model
{
    protected $fillable = [
        'category_code',
        'question',
        'answer',
        'sort_order',
        'is_published',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'question' => 'array',
            'answer' => 'array',
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
        return BilingualFields::isComplete($this->question)
            && BilingualFields::isComplete($this->answer);
    }
}
