<?php

namespace App\Models\Tariff;

use App\Models\Catalogue\Service;
use App\Models\Catalogue\VehicleCategory;
use App\Models\Centre\Centre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TariffItem extends Model
{
    protected $fillable = [
        'tariff_version_id',
        'vehicle_category_id',
        'service_id',
        'amount_xaf',
        'validity_notes',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'validity_notes' => 'array',
        ];
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(TariffVersion::class, 'tariff_version_id');
    }

    public function vehicleCategory(): BelongsTo
    {
        return $this->belongsTo(VehicleCategory::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function centres(): BelongsToMany
    {
        return $this->belongsToMany(Centre::class, 'tariff_item_centre');
    }

    public function appliesAtCentre(int $centreId): bool
    {
        return $this->centres->contains('id', $centreId);
    }

    public function appliesToService(?int $serviceId): bool
    {
        if ($this->service_id === null) {
            return true;
        }

        return $serviceId !== null && $this->service_id === $serviceId;
    }
}
