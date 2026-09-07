<?php

namespace Database\Seeders;

use App\Domain\Enums\AdminRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminBootstrapSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AdminRolesSeeder::class);

        $email = (string) env('ADMIN_BOOTSTRAP_EMAIL', 'admin@g3control.local');
        $password = (string) env('ADMIN_BOOTSTRAP_PASSWORD', 'G3Control!Dev2026');

        $user = User::query()->firstOrNew(['email' => $email]);
        $user->name = $user->exists ? $user->name : 'Super Admin';
        $user->password = Hash::make($password);
        $user->is_active = true;
        $user->email_verified_at ??= now();
        $user->save();

        $user->syncRoles([AdminRole::SuperAdmin->value]);
    }
}
