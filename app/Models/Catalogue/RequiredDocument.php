<?php

namespace App\Models\Catalogue;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequiredDocument extends Model
{
    protected $fillable = [
        'label',
        'vehicle_category_id',
        'service_id',
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

    public function vehicleCategory(): BelongsTo
    {
        return $this->belongsTo(VehicleCategory::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function scopeForBooking($query, int $serviceId, int $vehicleCategoryId)
    {
        return $query->where(function ($query) use ($serviceId, $vehicleCategoryId): void {
            $query->where(function ($query) use ($serviceId, $vehicleCategoryId): void {
                $query->where('service_id', $serviceId)
                    ->where('vehicle_category_id', $vehicleCategoryId);
            })->orWhere(function ($query) use ($serviceId): void {
                $query->where('service_id', $serviceId)
                    ->whereNull('vehicle_category_id');
            })->orWhere(function ($query) use ($vehicleCategoryId): void {
                $query->where('vehicle_category_id', $vehicleCategoryId)
                    ->whereNull('service_id');
            });
        })->orderBy('sort_order');
    }
}
