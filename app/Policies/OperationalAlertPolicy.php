<?php

namespace App\Policies;

use App\Models\Centre\OperationalAlert;
use App\Models\User;

class OperationalAlertPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super_admin', 'operations_admin']);
    }

    public function view(User $user, OperationalAlert $alert): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, OperationalAlert $alert): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, OperationalAlert $alert): bool
    {
        return $this->viewAny($user);
    }

    public function deleteAny(User $user): bool
    {
        return $this->viewAny($user);
    }
}
