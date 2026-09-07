<?php

use App\Filament\Auth\Login;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['admin.mfa_required' => false]);
    seedAdminRoles();
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

test('active admin with role can access filament panel FR-AD-01', function () {
    $user = createAdminUser('operations_admin');

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk();
});

test('user without admin role cannot access panel', function () {
    $user = User::factory()->create();

    expect($user->canAccessPanel(Filament::getPanel('admin')))->toBeFalse();
});

test('inactive admin cannot access panel', function () {
    $user = createAdminUser('super_admin', ['is_active' => false]);

    expect($user->canAccessPanel(Filament::getPanel('admin')))->toBeFalse();
});

test('admin can log in through custom login page', function () {
    $user = createAdminUser('super_admin', [
        'email' => 'admin@g3control.test',
        'password' => bcrypt('SecurePass123'),
    ]);

    Livewire::test(Login::class)
        ->fillForm([
            'email' => 'admin@g3control.test',
            'password' => 'SecurePass123',
        ])
        ->call('authenticate')
        ->assertHasNoFormErrors();

    expect(auth()->id())->toBe($user->id);
});

test('guest is redirected to branded login page', function () {
    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('Connexion')
        ->assertSee('G3')
        ->assertSee('Control');
});

test('guest cannot access dashboard', function () {
    $this->get('/admin')
        ->assertRedirect('/admin/login');
});

test('authenticated admin sees french dashboard heading', function () {
    $user = createAdminUser('super_admin');

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Tableau de bord');
});
