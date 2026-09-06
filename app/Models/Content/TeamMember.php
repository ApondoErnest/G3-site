<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'role_title',
        'bio',
        'display_publicly',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role_title' => 'array',
            'bio' => 'array',
            'display_publicly' => 'boolean',
        ];
    }

    public function scopeDisplayPublicly($query)
    {
        return $query->where('display_publicly', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
