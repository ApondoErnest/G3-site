<?php

namespace App\Support;

use App\Models\Centre\Centre;
use App\Models\User;

final class CentreAccess
{
    public static function userCanAccessCentre(User $user, Centre $centre): bool
    {
        return self::userCanAccessCentreId($user, $centre->id);
    }

    public static function userCanAccessCentreId(User $user, int $centreId): bool
    {
        if ($user->hasRole(['super_admin', 'operations_admin'])) {
            return true;
        }

        return $user->centreScopes()->where('centre_id', $centreId)->exists();
    }
}
