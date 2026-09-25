<?php

namespace App\Policies;

use App\Models\Centre\CentrePhone;
use App\Models\User;
use App\Support\CentreAccess;

class CentrePhonePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole([
            'super_admin',
            'operations_admin',
            'centre_manager',
        ]);
    }

    public function view(User $user, CentrePhone $centrePhone): bool
    {
        return $this->update($user, $centrePhone);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, CentrePhone $centrePhone): bool
    {
        if ($user->hasRole(['super_admin', 'operations_admin'])) {
            return true;
        }

        if ($user->hasRole('centre_manager')) {
            return CentreAccess::userCanAccessCentreId($user, $centrePhone->centre_id);
        }

        return false;
    }

    public function delete(User $user, CentrePhone $centrePhone): bool
    {
        return $this->update($user, $centrePhone);
    }

    public function restore(User $user, CentrePhone $centrePhone): bool
    {
        return false;
    }

    public function forceDelete(User $user, CentrePhone $centrePhone): bool
    {
        return false;
    }
}
