<?php

use App\Filament\Pages\AuditLog;
use App\Filament\Resources\Content\ContentBlocks\ContentBlocks\ContentBlockResource;
use Database\Seeders\BaselineCentresSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['admin.mfa_required' => false]);
    seedAdminRoles();
    $this->seed(BaselineCentresSeeder::class);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

test('super admin can access audit log', function (): void {
    Activity::query()->create([
        'log_name' => 'admin',
        'description' => 'Test audit entry',
        'subject_type' => null,
        'subject_id' => null,
        'causer_type' => null,
        'causer_id' => null,
        'properties' => [],
    ]);

    $user = createAdminUser('super_admin');

    $this->actingAs($user)
        ->get(AuditLog::getUrl())
        ->assertOk()
        ->assertSee(__('admin.audit.title', locale: 'fr'))
        ->assertSee('Test audit entry');
});

test('operations admin can access audit log', function (): void {
    $user = createAdminUser('operations_admin');

    $this->actingAs($user)
        ->get(AuditLog::getUrl())
        ->assertOk();
});

test('content editor cannot access audit log', function (): void {
    $user = createAdminUser('content_editor');

    $this->actingAs($user)
        ->get(AuditLog::getUrl())
        ->assertForbidden();
});

test('reception officer cannot access audit log', function (): void {
    $user = createAdminUser('reception_officer');

    $this->actingAs($user)
        ->get(AuditLog::getUrl())
        ->assertForbidden();

    $this->actingAs($user)
        ->get(ContentBlockResource::getUrl('index'))
        ->assertForbidden();
});
