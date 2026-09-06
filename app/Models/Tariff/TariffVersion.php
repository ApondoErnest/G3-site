<?php

namespace App\Models\Tariff;

use App\Domain\Enums\TariffVersionStatus;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TariffVersion extends Model
{
    protected $fillable = [
        'label',
        'status',
        'effective_from',
        'effective_until',
        'reviewed_at',
        'reviewed_by',
        'published_at',
        'published_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TariffVersionStatus::class,
            'effective_from' => 'date',
            'effective_until' => 'date',
            'reviewed_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(TariffItem::class)->orderBy('sort_order');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function isEffectiveOn(CarbonInterface $date): bool
    {
        $day = $date->toDateString();

        if ($this->effective_from->toDateString() > $day) {
            return false;
        }

        if ($this->effective_until !== null && $this->effective_until->toDateString() < $day) {
            return false;
        }

        return true;
    }

    public function canPublish(): bool
    {
        return $this->status === TariffVersionStatus::Reviewed
            && $this->items()->exists();
    }

    public function archive(): void
    {
        $this->status = TariffVersionStatus::Archived;
        $this->save();
    }
}
