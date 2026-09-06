<?php

namespace App\Actions\Appointment;

use App\Models\Appointment\AppointmentRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

trait AuthorizesAppointmentChanges
{
    protected function authorizeAppointmentChange(User $user, AppointmentRequest $appointment): void
    {
        if ($user->hasRole(['super_admin', 'operations_admin'])) {
            return;
        }

        if ($user->hasRole(['reception_officer', 'centre_manager'])
            && $user->centreScopes()->where('centre_id', $appointment->centre_id)->exists()) {
            return;
        }

        throw new AuthorizationException('You are not authorized to manage this appointment.');
    }
}
