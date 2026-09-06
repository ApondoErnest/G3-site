<?php

namespace App\Actions\Appointment;

use App\Actions\Appointment\Data\AppointmentRequestResult;
use App\Actions\Appointment\Data\CreateAppointmentRequestData;
use App\Actions\Catalogue\ResolveServiceAvailability;
use App\Actions\Schedule\ResolveCentreAvailability;
use App\Domain\Enums\AppointmentStatus;
use App\Domain\Enums\CentreStatus;
use App\Domain\Enums\HistoryActorType;
use App\Domain\ValueObjects\PhoneNumber;
use App\Domain\ValueObjects\RegistrationPlate;
use App\Events\AppointmentRequested;
use App\Models\Appointment\AppointmentRequest;
use App\Models\Centre\Centre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

final class CreateAppointmentRequest
{
    private const MESSAGE_KEY = 'appointments.request.received';

    public function __construct(
        private ResolveCentreAvailability $resolveCentreAvailability,
        private ResolveServiceAvailability $resolveServiceAvailability,
    ) {}

    public function __invoke(CreateAppointmentRequestData $data): AppointmentRequestResult
    {
        $this->assertWithinRateLimit($data);

        if ($data->idempotencyKey !== null) {
            $existing = AppointmentRequest::query()
                ->where('idempotency_key', $data->idempotencyKey)
                ->first();

            if ($existing !== null) {
                return new AppointmentRequestResult(
                    publicReference: (string) $existing->public_reference,
                    status: $existing->status,
                    messageKey: self::MESSAGE_KEY,
                    wasExisting: true,
                );
            }
        }

        $centre = Centre::query()->find($data->centreId);

        if ($centre === null || $centre->status !== CentreStatus::Active) {
            throw ValidationException::withMessages([
                'centreId' => ['The selected centre is not available.'],
            ]);
        }

        if (! $this->resolveServiceAvailability->__invoke(
            $data->serviceId,
            $data->centreId,
            $data->vehicleCategoryId,
        )) {
            throw ValidationException::withMessages([
                'serviceId' => ['The selected service is not available at this centre for the vehicle category.'],
            ]);
        }

        if (! $this->resolveCentreAvailability->isBookableOnDate(
            $data->centreId,
            $data->preferredDate,
            $data->preferredPeriod,
        )) {
            throw ValidationException::withMessages([
                'preferredDate' => ['The preferred time is outside this centre\'s opening hours.'],
            ]);
        }

        $plate = RegistrationPlate::fromInput($data->registration);
        $phone = PhoneNumber::fromInput($data->contactPhone);

        $appointment = DB::transaction(function () use ($data, $centre, $plate, $phone): AppointmentRequest {
            $draft = new AppointmentRequest([
                'centre_id' => $centre->id,
                'service_id' => $data->serviceId,
                'vehicle_category_id' => $data->vehicleCategoryId,
                'registration_normalized' => $plate->normalized,
                'registration_display' => $plate->display,
                'preferred_date' => $data->preferredDate->toDateString(),
                'preferred_period' => $data->preferredPeriod,
                'contact_name' => $data->contactName,
                'contact_phone_e164' => $phone->e164,
                'contact_email' => $data->contactEmail,
                'preferred_channel' => $data->preferredChannel,
                'locale' => $data->locale,
                'status' => AppointmentStatus::Received,
                'idempotency_key' => $data->idempotencyKey,
            ]);

            $draft->public_reference = $draft->uniquePublicReference();
            $draft->save();

            $draft->recordTransition(
                AppointmentStatus::Received,
                HistoryActorType::System,
            );

            return $draft->refresh();
        });

        DB::afterCommit(function () use ($appointment): void {
            Event::dispatch(new AppointmentRequested(
                appointmentId: $appointment->id,
                publicReference: (string) $appointment->public_reference,
                centreId: $appointment->centre_id,
                locale: $appointment->locale->value,
            ));
        });

        $this->hitRateLimit($data);

        return new AppointmentRequestResult(
            publicReference: (string) $appointment->public_reference,
            status: $appointment->status,
            messageKey: self::MESSAGE_KEY,
        );
    }

    private function assertWithinRateLimit(CreateAppointmentRequestData $data): void
    {
        $key = $this->rateLimitKey($data);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new TooManyRequestsHttpException(
                RateLimiter::availableIn($key),
                'Too many appointment requests.',
            );
        }
    }

    private function hitRateLimit(CreateAppointmentRequestData $data): void
    {
        RateLimiter::hit($this->rateLimitKey($data), 3600);
    }

    private function rateLimitKey(CreateAppointmentRequestData $data): string
    {
        $identifier = $data->rateLimitKey ?? request()->ip() ?? '127.0.0.1';

        return 'appointment-submit:'.$identifier;
    }
}
