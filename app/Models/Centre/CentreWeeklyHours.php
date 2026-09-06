<?php

namespace App\Models\Centre;

use App\Domain\Enums\Weekday;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentreWeeklyHours extends Model
{
    protected $table = 'centre_weekly_hours';

    protected $fillable = [
        'centre_id',
        'weekday',
        'is_open',
        'opens_at',
        'closes_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'weekday' => Weekday::class,
            'is_open' => 'boolean',
        ];
    }

    public function centre(): BelongsTo
    {
        return $this->belongsTo(Centre::class);
    }
}
