<?php

namespace App\Policies;

use App\Models\Content\PageSeo;
use App\Models\User;

class PageSeoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super_admin', 'content_editor']);
    }

    public function view(User $user, PageSeo $pageSeo): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, PageSeo $pageSeo): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, PageSeo $pageSeo): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
