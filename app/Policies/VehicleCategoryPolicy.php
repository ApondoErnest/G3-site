<?php

namespace App\Policies;

use App\Models\Catalogue\VehicleCategory;
use App\Models\User;

class VehicleCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole([
            'super_admin',
            'operations_admin',
            'content_editor',
        ]);
    }

    public function view(User $user, VehicleCategory $category): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['super_admin', 'content_editor']);
    }

    public function update(User $user, VehicleCategory $category): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, VehicleCategory $category): bool
    {
        return $this->create($user);
    }

    public function deleteAny(User $user): bool
    {
        return $this->create($user);
    }
}
