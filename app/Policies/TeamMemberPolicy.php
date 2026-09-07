<?php

namespace App\Policies;

use App\Models\Content\TeamMember;
use App\Models\User;

class TeamMemberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super_admin', 'content_editor']);
    }

    public function view(User $user, TeamMember $teamMember): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, TeamMember $teamMember): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, TeamMember $teamMember): bool
    {
        return $this->viewAny($user);
    }

    public function deleteAny(User $user): bool
    {
        return $this->viewAny($user);
    }
}
