<?php

namespace App\Actions\Identity;

use App\Domain\Enums\AdminRole;
use App\Models\Identity\AdminUserScope;
use App\Models\User;
use Illuminate\Validation\ValidationException;

final class SaveAdminUserAccess
{
    /**
     * @param  list<string>  $roles
     * @param  list<int>  $centreIds
     */
    public function __invoke(User $user, array $roles, array $centreIds, bool $isActive): void
    {
        $roles = array_values(array_intersect($roles, AdminRole::values()));

        $this->assertRetainsSuperAdmin($user, $roles, $isActive);

        $user->syncRoles($roles);

        $user->centreScopes()->delete();

        $needsCentre = array_intersect($roles, [
            AdminRole::CentreManager->value,
            AdminRole::ReceptionOfficer->value,
        ]) !== [];

        if (! $needsCentre) {
            return;
        }

        foreach (array_unique($centreIds) as $centreId) {
            AdminUserScope::query()->create([
                'user_id' => $user->id,
                'centre_id' => $centreId,
                'created_at' => now(),
            ]);
        }
    }

    /**
     * @param  list<string>  $roles
     */
    public function assertRetainsSuperAdmin(User $user, array $roles, bool $isActive): void
    {
        if (! $user->exists || ! $user->hasRole(AdminRole::SuperAdmin->value)) {
            return;
        }

        $keepsAccess = $isActive && in_array(AdminRole::SuperAdmin->value, $roles, true);

        if ($keepsAccess) {
            return;
        }

        $anotherActiveSuperAdmin = User::query()
            ->role(AdminRole::SuperAdmin->value)
            ->where('is_active', true)
            ->whereKeyNot($user->id)
            ->exists();

        if ($anotherActiveSuperAdmin) {
            return;
        }

        throw ValidationException::withMessages([
            'roles' => __('admin.users.errors.last_super_admin'),
        ]);
    }
}
