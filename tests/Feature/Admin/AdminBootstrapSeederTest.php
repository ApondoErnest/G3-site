<?php

use App\Models\User;
use Database\Seeders\AdminBootstrapSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin bootstrap seeder creates super admin with panel access', function (): void {
    (new AdminBootstrapSeeder)->run();

    $user = User::query()->where('email', 'admin@g3control.local')->first();

    expect($user)->not->toBeNull()
        ->and($user->is_active)->toBeTrue()
        ->and($user->hasRole('super_admin'))->toBeTrue()
        ->and($user->canAccessPanel(Filament::getPanel('admin')))->toBeTrue();
});

test('admin bootstrap seeder is idempotent', function (): void {
    (new AdminBootstrapSeeder)->run();
    (new AdminBootstrapSeeder)->run();

    expect(User::query()->where('email', 'admin@g3control.local')->count())->toBe(1);
});
