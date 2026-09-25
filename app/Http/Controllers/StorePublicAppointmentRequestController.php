<?php

namespace App\Http\Controllers;

use App\Actions\Appointment\CreateAppointmentRequest;
use App\Actions\Appointment\Data\CreateAppointmentRequestData;
use App\Domain\Enums\Locale;
use App\Domain\Enums\PreferredChannel;
use App\Domain\Enums\PreferredPeriod;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class StorePublicAppointmentRequestController extends Controller
{
    public function __invoke(Request $request, CreateAppointmentRequest $createAppointmentRequest): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'centre_id' => ['required', 'integer'],
            'service_id' => ['required', 'integer'],
            'vehicle_category_id' => ['required', 'integer'],
            'registration' => ['required', 'string', 'max:32'],
            'preferred_date' => ['required', 'date'],
            'preferred_period' => ['required', Rule::enum(PreferredPeriod::class)],
            'full_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
        ], [
            'centre_id.required' => __('public.security.appointment_errors.centre_id'),
            'service_id.required' => __('public.security.appointment_errors.service_id'),
            'vehicle_category_id.required' => __('public.security.appointment_errors.vehicle_category_id'),
            'registration.required' => __('public.security.appointment_errors.registration'),
            'preferred_date.required' => __('public.security.appointment_errors.preferred_date'),
            'preferred_period.required' => __('public.security.appointment_errors.preferred_period'),
            'full_name.required' => __('public.security.appointment_errors.full_name'),
            'phone.required' => __('public.security.appointment_errors.phone'),
            'email.email' => __('public.security.appointment_errors.email'),
        ]);

        $idempotencyKey = (string) $request->session()->get('appointment_idempotency', '');

        if ($idempotencyKey === '') {
            $idempotencyKey = (string) Str::uuid();
            $request->session()->put('appointment_idempotency', $idempotencyKey);
        }

        try {
            $result = $createAppointmentRequest(new CreateAppointmentRequestData(
                centreId: (int) $validated['centre_id'],
                serviceId: (int) $validated['service_id'],
                vehicleCategoryId: (int) $validated['vehicle_category_id'],
                registration: $validated['registration'],
                preferredDate: CarbonImmutable::parse($validated['preferred_date'], 'Africa/Douala'),
                preferredPeriod: PreferredPeriod::from($validated['preferred_period']),
                contactName: $validated['full_name'],
                contactPhone: $validated['phone'],
                contactEmail: $validated['email'] ?? null,
                preferredChannel: PreferredChannel::Phone,
                locale: Locale::fromString(app()->getLocale()),
                idempotencyKey: $idempotencyKey,
                rateLimitKey: $request->ip(),
            ));
        } catch (TooManyRequestsHttpException) {
            return $this->failed($request, ['form' => __('public.security.too_many')], 429);
        } catch (ValidationException $exception) {
            return $this->failed($request, collect($exception->errors())->map(fn (array $messages): string => $messages[0])->all());
        }

        if (! $result->wasExisting) {
            $request->session()->put('appointment_idempotency', (string) Str::uuid());
        }

        $payload = [
            'reference' => $result->publicReference,
            'message' => __('public.security.appointment_received'),
        ];

        if ($request->expectsJson()) {
            return response()->json($payload);
        }

        return back()->with('appointment_received', $payload);
    }

    /**
     * @param  array<string, string>  $errors
     */
    private function failed(Request $request, array $errors, int $status = 422): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => reset($errors) ?: __('public.security.appointment_errors.form'),
                'errors' => collect($errors)->map(fn (string $message): array => [$message])->all(),
            ], $status);
        }

        return back()->withErrors($errors)->withInput();
    }
}
