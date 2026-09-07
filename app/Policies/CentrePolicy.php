<?php

namespace App\Policies;

use App\Models\Centre\Centre;
use App\Models\User;
use App\Support\CentreAccess;

class CentrePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole([
            'super_admin',
            'operations_admin',
            'centre_manager',
            'reception_officer',
            'content_editor',
        ]);
    }

    public function view(User $user, Centre $centre): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Centre $centre): bool
    {
        if ($user->hasRole(['super_admin', 'operations_admin'])) {
            return true;
        }

        if ($user->hasRole('centre_manager')) {
            return CentreAccess::userCanAccessCentre($user, $centre);
        }

        return false;
    }
}
