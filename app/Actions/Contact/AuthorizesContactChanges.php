<?php

namespace App\Actions\Contact;

use App\Models\Contact\ContactMessage;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

trait AuthorizesContactChanges
{
    protected function authorizeContactChange(User $user, ContactMessage $message): void
    {
        if ($user->hasRole(['super_admin', 'operations_admin'])) {
            return;
        }

        if ($user->hasRole(['reception_officer', 'centre_manager'])) {
            if ($message->centre_id === null) {
                return;
            }

            if ($user->centreScopes()->where('centre_id', $message->centre_id)->exists()) {
                return;
            }
        }

        throw new AuthorizationException('You are not authorized to manage this contact message.');
    }
}
