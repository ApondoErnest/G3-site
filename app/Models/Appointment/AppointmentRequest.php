<?php

namespace App\Models\Appointment;

use App\Casts\PublicReferenceCast;
use App\Domain\Enums\AppointmentStatus;
use App\Domain\Enums\HistoryActorType;
use App\Domain\Enums\Locale;
use App\Domain\Enums\PreferredChannel;
use App\Domain\Enums\PreferredPeriod;
use App\Domain\ValueObjects\PhoneNumber;
use App\Domain\ValueObjects\PublicReference;
use App\Models\Catalogue\Service;
use App\Models\Catalogue\VehicleCategory;
use App\Models\Centre\Centre;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppointmentRequest extends Model
{
    protected $fillable = [
        'public_reference',
        'centre_id',
        'service_id',
        'vehicle_category_id',
        'registration_normalized',
        'registration_display',
        'preferred_date',
        'preferred_period',
        'contact_name',
        'contact_phone_e164',
        'contact_email',
        'preferred_channel',
        'locale',
        'status',
        'idempotency_key',
        'finalized_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'public_reference' => PublicReferenceCast::class,
            'preferred_date' => 'date',
            'preferred_period' => PreferredPeriod::class,
            'preferred_channel' => PreferredChannel::class,
            'locale' => Locale::class,
            'status' => AppointmentStatus::class,
            'finalized_at' => 'datetime',
        ];
    }

    public function centre(): BelongsTo
    {
        return $this->belongsTo(Centre::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function vehicleCategory(): BelongsTo
    {
        return $this->belongsTo(VehicleCategory::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(AppointmentStatusHistory::class, 'appointment_request_id')
            ->orderBy('created_at');
    }

    public function internalNotes(): HasMany
    {
        return $this->hasMany(AppointmentInternalNote::class, 'appointment_request_id')
            ->orderBy('created_at');
    }

    public function isTrackableWith(string $phoneOrPlate): bool
    {
        $normalized = strtoupper(preg_replace('/\s+/', '', trim($phoneOrPlate)) ?? '');

        if ($normalized === '') {
            return false;
        }

        if (str_starts_with($normalized, '+') || ctype_digit($normalized)) {
            try {
                $phone = PhoneNumber::fromInput($phoneOrPlate);

                return $this->contact_phone_e164 === $phone->e164;
            } catch (\InvalidArgumentException) {
                return false;
            }
        }

        return $this->registration_normalized === $normalized;
    }

    public function recordTransition(
        AppointmentStatus $to,
        HistoryActorType $actorType,
        ?User $user = null,
        ?array $publicNote = null,
    ): AppointmentStatusHistory {
        return $this->statusHistories()->create([
            'status' => $to,
            'public_note' => $publicNote,
            'actor_type' => $actorType,
            'actor_id' => $user?->id,
            'created_at' => now(),
        ]);
    }

    public function uniquePublicReference(): PublicReference
    {
        for ($attempt = 0; $attempt < 10; $attempt++) {
            $reference = PublicReference::generate();

            if (! self::query()->where('public_reference', $reference->value)->exists()) {
                return $reference;
            }
        }

        throw new \RuntimeException('Unable to generate a unique public reference.');
    }
}
