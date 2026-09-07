<?php

namespace App\Policies;

use App\Models\Catalogue\RequiredDocument;
use App\Models\User;

class RequiredDocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole([
            'super_admin',
            'operations_admin',
            'content_editor',
        ]);
    }

    public function view(User $user, RequiredDocument $document): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['super_admin', 'content_editor']);
    }

    public function update(User $user, RequiredDocument $document): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, RequiredDocument $document): bool
    {
        return $this->create($user);
    }

    public function deleteAny(User $user): bool
    {
        return $this->create($user);
    }
}
