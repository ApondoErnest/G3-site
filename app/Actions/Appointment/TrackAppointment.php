<?php

namespace App\Actions\Appointment;

use App\Actions\Appointment\Data\TrackAppointmentData;
use App\Actions\Appointment\Data\TrackingResult;
use App\Actions\Appointment\Data\TrackingTimelineEntry;
use App\Domain\Appointment\TrackingLookupFailedException;
use App\Domain\ValueObjects\PublicReference;
use App\Models\Appointment\AppointmentRequest;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\RateLimiter;

final class TrackAppointment
{
    private const RATE_LIMIT_MAX = 10;

    private const RATE_LIMIT_DECAY_SECONDS = 3600;

    public function __invoke(TrackAppointmentData $data): TrackingResult
    {
        $this->assertWithinRateLimit($data);

        try {
            $reference = PublicReference::fromString($data->publicReference);
        } catch (\InvalidArgumentException) {
            $this->recordFailedAttempt($data);

            throw TrackingLookupFailedException::generic();
        }

        $appointment = AppointmentRequest::query()
            ->with(['centre', 'statusHistories'])
            ->where('public_reference', $reference->value)
            ->first();

        if ($appointment === null || ! $appointment->isTrackableWith($data->phoneOrRegistration)) {
            $this->recordFailedAttempt($data);

            throw TrackingLookupFailedException::generic();
        }

        return $this->buildResult($appointment);
    }

    private function buildResult(AppointmentRequest $appointment): TrackingResult
    {
        $timeline = $appointment->statusHistories
            ->map(fn ($history) => new TrackingTimelineEntry(
                status: $history->status,
                labelKey: 'appointments.status.'.$history->status->value,
                at: CarbonImmutable::parse($history->created_at),
            ))
            ->values()
            ->all();

        return new TrackingResult(
            publicReference: (string) $appointment->public_reference,
            currentStatus: $appointment->status,
            timeline: $timeline,
            centreName: $appointment->centre->translatedName($appointment->locale),
        );
    }

    private function assertWithinRateLimit(TrackAppointmentData $data): void
    {
        if (RateLimiter::tooManyAttempts($this->rateLimitKey($data), self::RATE_LIMIT_MAX)) {
            throw TrackingLookupFailedException::generic();
        }
    }

    private function recordFailedAttempt(TrackAppointmentData $data): void
    {
        RateLimiter::hit($this->rateLimitKey($data), self::RATE_LIMIT_DECAY_SECONDS);
    }

    private function rateLimitKey(TrackAppointmentData $data): string
    {
        $identifier = $data->rateLimitKey ?? request()->ip() ?? '127.0.0.1';

        return 'tracking-lookup:'.$identifier;
    }
}
