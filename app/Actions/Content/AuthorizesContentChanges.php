<?php

namespace App\Actions\Content;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

trait AuthorizesContentChanges
{
    protected function authorizeContentChange(User $user): void
    {
        if ($user->hasRole(['super_admin', 'content_editor'])) {
            return;
        }

        throw new AuthorizationException('You are not authorized to manage content.');
    }
}
