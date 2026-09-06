<?php

namespace App\Models\Content;

use App\Models\Centre\Centre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Equipment extends Model
{
    protected $table = 'equipment';

    protected $fillable = [
        'code',
        'label',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'label' => 'array',
        ];
    }

    public function centres(): BelongsToMany
    {
        return $this->belongsToMany(Centre::class, 'centre_equipment');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
