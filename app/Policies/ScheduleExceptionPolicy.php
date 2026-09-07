<?php

namespace App\Policies;

use App\Models\Centre\ScheduleException;
use App\Models\User;
use App\Support\CentreAccess;

class ScheduleExceptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole([
            'super_admin',
            'operations_admin',
            'centre_manager',
        ]);
    }

    public function view(User $user, ScheduleException $exception): bool
    {
        return $this->update($user, $exception);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, ScheduleException $exception): bool
    {
        if ($user->hasRole(['super_admin', 'operations_admin'])) {
            return true;
        }

        if ($user->hasRole('centre_manager') && $exception->centre_id !== null) {
            return CentreAccess::userCanAccessCentreId($user, $exception->centre_id);
        }

        return false;
    }

    public function delete(User $user, ScheduleException $exception): bool
    {
        return $this->update($user, $exception);
    }
}
