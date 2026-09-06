<?php

namespace App\Models\Appointment;

use App\Domain\Enums\AppointmentStatus;
use App\Domain\Enums\HistoryActorType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentStatusHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'appointment_request_id',
        'status',
        'public_note',
        'actor_type',
        'actor_id',
        'created_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => AppointmentStatus::class,
            'public_note' => 'array',
            'actor_type' => HistoryActorType::class,
            'created_at' => 'datetime',
        ];
    }

    public function appointmentRequest(): BelongsTo
    {
        return $this->belongsTo(AppointmentRequest::class, 'appointment_request_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
