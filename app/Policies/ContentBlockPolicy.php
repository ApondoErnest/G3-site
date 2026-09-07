<?php

namespace App\Policies;

use App\Models\Content\ContentBlock;
use App\Models\User;

class ContentBlockPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super_admin', 'content_editor']);
    }

    public function view(User $user, ContentBlock $contentBlock): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, ContentBlock $contentBlock): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, ContentBlock $contentBlock): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
