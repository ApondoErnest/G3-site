<?php

namespace App\Policies;

use App\Models\Tariff\TariffVersion;
use App\Models\User;

class TariffVersionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole([
            'super_admin',
            'operations_admin',
            'centre_manager',
        ]);
    }

    public function view(User $user, TariffVersion $tariffVersion): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['super_admin', 'operations_admin']);
    }

    public function update(User $user, TariffVersion $tariffVersion): bool
    {
        return $user->hasRole(['super_admin', 'operations_admin']);
    }

    public function publish(User $user, TariffVersion $tariffVersion): bool
    {
        return $user->hasRole(['super_admin', 'operations_admin']);
    }
}
