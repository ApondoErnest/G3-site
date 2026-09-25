<?php

namespace App\Http\Controllers;

use App\Actions\Appointment\Data\TrackAppointmentData;
use App\Actions\Appointment\TrackAppointment;
use App\Domain\Appointment\TrackingLookupFailedException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TrackPublicAppointmentController extends Controller
{
    public function __invoke(Request $request, TrackAppointment $trackAppointment): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'request_reference' => ['required', 'string', 'max:32'],
            'tracking_phone' => ['required', 'string', 'max:32'],
        ], [
            'request_reference.required' => __('public.security.appointment_errors.request_reference'),
            'tracking_phone.required' => __('public.security.appointment_errors.tracking_phone'),
        ]);

        try {
            $result = $trackAppointment(new TrackAppointmentData(
                publicReference: $validated['request_reference'],
                phoneOrRegistration: $validated['tracking_phone'],
                rateLimitKey: $request->ip(),
            ));
        } catch (TrackingLookupFailedException) {
            $message = __('public.security.tracking_failed');

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'errors' => ['tracking' => [$message]],
                ], 422);
            }

            return back()->withErrors(['tracking' => $message])->withInput();
        }

        $payload = [
            'message' => __('public.security.tracking_found'),
            'reference' => $result->publicReference,
            'status' => __('public.security.tracking_statuses.'.$result->currentStatus->value),
            'centre' => $result->centreName,
        ];

        if ($request->expectsJson()) {
            return response()->json($payload);
        }

        return back()->with('tracking_result', $payload);
    }
}
