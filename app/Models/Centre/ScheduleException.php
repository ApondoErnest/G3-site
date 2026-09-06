<?php

namespace App\Models\Centre;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleException extends Model
{
    protected $fillable = [
        'applies_to_all_centres',
        'centre_id',
        'starts_on',
        'ends_on',
        'is_open',
        'opens_at',
        'closes_at',
        'reason',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'applies_to_all_centres' => 'boolean',
            'starts_on' => 'date',
            'ends_on' => 'date',
            'is_open' => 'boolean',
            'reason' => 'array',
        ];
    }

    public function centre(): BelongsTo
    {
        return $this->belongsTo(Centre::class);
    }
}
