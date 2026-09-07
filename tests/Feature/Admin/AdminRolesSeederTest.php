<?php

use App\Domain\Enums\AdminRole;
use Database\Seeders\AdminRolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('admin roles seeder creates five baseline roles FR-AD-02', function () {
    (new AdminRolesSeeder)->run();

    expect(Role::query()->pluck('name')->sort()->values()->all())
        ->toBe(collect(AdminRole::cases())->pluck('value')->sort()->values()->all());
});

test('admin roles seeder is idempotent', function () {
    (new AdminRolesSeeder)->run();
    (new AdminRolesSeeder)->run();

    expect(Role::query()->count())->toBe(count(AdminRole::cases()));
});
