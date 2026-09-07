<?php

namespace App\Policies;

use App\Models\Appointment\AppointmentRequest;
use App\Models\User;
use App\Support\CentreAccess;

class AppointmentRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole([
            'super_admin',
            'operations_admin',
            'centre_manager',
            'reception_officer',
        ]);
    }

    public function view(User $user, AppointmentRequest $appointment): bool
    {
        return $this->update($user, $appointment);
    }

    public function update(User $user, AppointmentRequest $appointment): bool
    {
        if ($user->hasRole(['super_admin', 'operations_admin'])) {
            return true;
        }

        if ($user->hasRole(['reception_officer', 'centre_manager'])) {
            return CentreAccess::userCanAccessCentreId($user, $appointment->centre_id);
        }

        return false;
    }
}
