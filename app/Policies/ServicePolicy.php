<?php

namespace App\Policies;

use App\Models\Catalogue\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole([
            'super_admin',
            'operations_admin',
            'content_editor',
        ]);
    }

    public function view(User $user, Service $service): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['super_admin', 'content_editor']);
    }

    public function update(User $user, Service $service): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, Service $service): bool
    {
        return $this->create($user);
    }

    public function deleteAny(User $user): bool
    {
        return $this->create($user);
    }
}
