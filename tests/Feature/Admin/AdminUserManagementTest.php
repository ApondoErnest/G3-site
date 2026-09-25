<?php

use App\Domain\Enums\AdminRole;
use App\Filament\Resources\System\Users\Users\Pages\CreateUser;
use App\Filament\Resources\System\Users\Users\Pages\EditUser;
use App\Filament\Resources\System\Users\Users\Pages\ListUsers;
use App\Filament\Resources\System\Users\Users\UserResource;
use App\Models\User;
use Database\Seeders\BaselineCentresSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['admin.mfa_required' => false]);
    seedAdminRoles();
    $this->seed(BaselineCentresSeeder::class);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

test('only a super administrator can open user management', function (): void {
    $operations = createAdminUser('operations_admin');
    $editor = createAdminUser('content_editor');

    $this->actingAs($operations)
        ->get(UserResource::getUrl('index'))
        ->assertForbidden();

    $this->actingAs($editor)
        ->get(UserResource::getUrl('index'))
        ->assertForbidden();

    $superAdmin = createAdminUser('super_admin');

    $this->actingAs($superAdmin)
        ->get(UserResource::getUrl('index'))
        ->assertOk()
        ->assertSee(__('admin.users.navigation', locale: 'fr'));

    Livewire::actingAs($superAdmin)
        ->test(ListUsers::class)
        ->assertActionVisible('create');
});

test('super administrator creates a reception officer scoped to one centre', function (): void {
    $superAdmin = createAdminUser('super_admin');
    $centreId = centreId('ecole-de-police');

    Livewire::actingAs($superAdmin)
        ->test(CreateUser::class)
        ->fillForm([
            'name' => 'Awa N.',
            'email' => 'awa@g3control.local',
            'password' => 'G3Control!Dev1',
            'password_confirmation' => 'G3Control!Dev1',
            'is_active' => true,
            'roles' => [AdminRole::ReceptionOfficer->value],
            'centre_ids' => [$centreId],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $user = User::query()->where('email', 'awa@g3control.local')->first();

    expect($user)->not->toBeNull()
        ->and($user->hasRole(AdminRole::ReceptionOfficer->value))->toBeTrue()
        ->and($user->centreScopes()->pluck('centre_id')->all())->toBe([$centreId]);
});

test('a reception officer cannot be saved without an assigned centre', function (): void {
    $superAdmin = createAdminUser('super_admin');

    Livewire::actingAs($superAdmin)
        ->test(CreateUser::class)
        ->fillForm([
            'name' => 'Awa N.',
            'email' => 'awa-missing-centre@g3control.local',
            'password' => 'G3Control!Dev1',
            'password_confirmation' => 'G3Control!Dev1',
            'is_active' => true,
            'roles' => [AdminRole::ReceptionOfficer->value],
        ])
        ->call('create')
        ->assertHasFormErrors(['centre_ids' => 'required'])
        ->assertSee('Ce champ est obligatoire.');

    expect(User::query()->where('email', 'awa-missing-centre@g3control.local')->exists())->toBeFalse();
});

test('the last active super administrator cannot be removed or deactivated', function (): void {
    $superAdmin = createAdminUser('super_admin');

    Livewire::actingAs($superAdmin)
        ->test(EditUser::class, ['record' => $superAdmin->id])
        ->fillForm([
            'roles' => [AdminRole::OperationsAdmin->value],
            'is_active' => true,
        ])
        ->call('save')
        ->assertHasFormErrors(['roles']);

    expect($superAdmin->fresh()->hasRole(AdminRole::SuperAdmin->value))->toBeTrue()
        ->and($superAdmin->fresh()->is_active)->toBeTrue();

    Livewire::actingAs($superAdmin)
        ->test(EditUser::class, ['record' => $superAdmin->id])
        ->fillForm([
            'roles' => [AdminRole::SuperAdmin->value],
            'is_active' => false,
        ])
        ->call('save')
        ->assertHasFormErrors(['roles']);

    expect($superAdmin->fresh()->is_active)->toBeTrue();
});

test('a super administrator cannot delete their own account', function (): void {
    $superAdmin = createAdminUser('super_admin');
    $reception = createAdminUser('reception_officer');

    Livewire::actingAs($superAdmin)
        ->test(EditUser::class, ['record' => $superAdmin->id])
        ->assertActionHidden('delete');

    Livewire::actingAs($superAdmin)
        ->test(EditUser::class, ['record' => $reception->id])
        ->assertActionVisible('delete')
        ->callAction('delete');

    expect(User::query()->whereKey($reception->id)->exists())->toBeFalse()
        ->and(User::query()->whereKey($superAdmin->id)->exists())->toBeTrue();
});
