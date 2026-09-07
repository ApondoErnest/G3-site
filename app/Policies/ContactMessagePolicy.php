<?php

namespace App\Policies;

use App\Models\Contact\ContactMessage;
use App\Models\User;
use App\Support\CentreAccess;

class ContactMessagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole([
            'super_admin',
            'operations_admin',
            'reception_officer',
            'centre_manager',
        ]);
    }

    public function view(User $user, ContactMessage $contactMessage): bool
    {
        return $this->update($user, $contactMessage);
    }

    public function update(User $user, ContactMessage $contactMessage): bool
    {
        if ($user->hasRole(['super_admin', 'operations_admin'])) {
            return true;
        }

        if ($user->hasRole(['reception_officer', 'centre_manager'])) {
            if ($contactMessage->centre_id === null) {
                return $user->centreScopes()->exists();
            }

            return CentreAccess::userCanAccessCentreId($user, $contactMessage->centre_id);
        }

        return false;
    }
}
