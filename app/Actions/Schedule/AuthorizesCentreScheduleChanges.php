<?php

namespace App\Actions\Schedule;

use App\Models\Centre\Centre;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

trait AuthorizesCentreScheduleChanges
{
    protected function authorizeCentreScheduleChange(User $user, Centre $centre): void
    {
        if ($user->hasRole(['super_admin', 'operations_admin'])) {
            return;
        }

        if ($user->hasRole('centre_manager') && $user->centreScopes()->where('centre_id', $centre->id)->exists()) {
            return;
        }

        throw new AuthorizationException('You are not authorized to change this centre schedule.');
    }
}
