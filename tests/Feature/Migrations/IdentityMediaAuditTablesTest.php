<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('users table has admin identity columns per docs/11-erd.md', function () {
    expect(Schema::hasColumns('users', [
        'is_active',
        'app_authentication_secret',
        'app_authentication_recovery_codes',
    ]))->toBeTrue();
});

test('admin user scopes table exists with expected columns', function () {
    expect(Schema::hasTable('admin_user_scopes'))->toBeTrue();

    expect(Schema::hasColumns('admin_user_scopes', [
        'id',
        'user_id',
        'centre_id',
        'created_at',
    ]))->toBeTrue();

    expect(Schema::hasColumn('admin_user_scopes', 'updated_at'))->toBeFalse();
});

test('admin user scopes enforces unique user and centre per uq_admin_user_scopes_user_centre', function () {
    $userId = insertIdentityUser();
    $centreId = insertCentre();

    insertAdminUserScope($userId, $centreId);

    expect(fn () => insertAdminUserScope($userId, $centreId))
        ->toThrow(QueryException::class);
});

test('admin user scopes cascade when user is removed per fk_admin_user_scopes_users', function () {
    $userId = insertIdentityUser();
    $centreId = insertCentre();
    $scopeId = insertAdminUserScope($userId, $centreId);

    DB::table('users')->where('id', $userId)->delete();

    expect(DB::table('admin_user_scopes')->where('id', $scopeId)->exists())->toBeFalse();
});

test('admin user scopes cascade when centre is removed per fk_admin_user_scopes_centres', function () {
    $userId = insertIdentityUser();
    $centreId = insertCentre();
    $scopeId = insertAdminUserScope($userId, $centreId);

    DB::table('centres')->where('id', $centreId)->delete();

    expect(DB::table('admin_user_scopes')->where('id', $scopeId)->exists())->toBeFalse();
});

test('spatie permission tables exist', function () {
    expect(Schema::hasTable('permissions'))->toBeTrue();
    expect(Schema::hasTable('roles'))->toBeTrue();
    expect(Schema::hasTable('model_has_permissions'))->toBeTrue();
    expect(Schema::hasTable('model_has_roles'))->toBeTrue();
    expect(Schema::hasTable('role_has_permissions'))->toBeTrue();
});

test('spatie media table exists with expected columns', function () {
    expect(Schema::hasTable('media'))->toBeTrue();

    expect(Schema::hasColumns('media', [
        'id',
        'model_type',
        'model_id',
        'collection_name',
        'file_name',
        'disk',
        'custom_properties',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('spatie activity log table exists with expected columns', function () {
    expect(Schema::hasTable('activity_log'))->toBeTrue();

    expect(Schema::hasColumns('activity_log', [
        'id',
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'event',
        'causer_type',
        'causer_id',
        'properties',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

function insertIdentityUser(): int
{
    return DB::table('users')->insertGetId([
        'name' => 'Scoped Manager',
        'email' => 'manager-'.uniqid().'@example.com',
        'password' => bcrypt('secret'),
        'is_active' => true,
        'app_authentication_secret' => null,
        'app_authentication_recovery_codes' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

function insertAdminUserScope(int $userId, int $centreId): int
{
    return DB::table('admin_user_scopes')->insertGetId([
        'user_id' => $userId,
        'centre_id' => $centreId,
        'created_at' => now(),
    ]);
}
