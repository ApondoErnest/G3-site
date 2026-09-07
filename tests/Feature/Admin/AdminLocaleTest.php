<?php

use App\Filament\Livewire\AdminLocaleSwitcher;
use App\Support\AdminLocale;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['admin.mfa_required' => false]);
    seedAdminRoles();
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

test('admin login defaults to french', function (): void {
    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('Connexion')
        ->assertSee('Se connecter')
        ->assertDontSee('Sign in');
});

test('admin locale can be switched to english on login page', function (): void {
    $this->get('/admin/locale/en')
        ->assertRedirect();

    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('Sign in')
        ->assertSee('Email address')
        ->assertDontSee('Connexion');
});

test('admin locale persists on dashboard after switch', function (): void {
    $user = createAdminUser('super_admin');

    $this->get('/admin/locale/en')
        ->assertRedirect();

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Dashboard')
        ->assertSee('New requests')
        ->assertDontSee('Tableau de bord');
});

test('admin locale can be switched back to french', function (): void {
    $user = createAdminUser('super_admin');

    $this->withSession([AdminLocale::SESSION_KEY => 'en'])
        ->get('/admin/locale/fr')
        ->assertRedirect();

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Tableau de bord')
        ->assertSee('Nouvelles demandes');
});

test('invalid admin locale falls back to default', function (): void {
    expect(AdminLocale::resolveFromSession())->toBe('fr');

    session([AdminLocale::SESSION_KEY => 'de']);
    expect(AdminLocale::resolveFromSession())->toBe('fr');
});

test('locale switcher is visible on login and dashboard', function (): void {
    $user = createAdminUser('super_admin');

    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('g3-locale-switcher', false)
        ->assertSee('wire:click="switchLocale', false);

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertSee('g3-locale-switcher', false)
        ->assertSee('wire:click="switchLocale', false);
});

test('livewire locale switch reloads dashboard in english', function (): void {
    $user = createAdminUser('super_admin');

    Livewire::actingAs($user)
        ->test(AdminLocaleSwitcher::class)
        ->call('switchLocale', 'en')
        ->assertRedirect('/admin');

    $this->actingAs($user)
        ->withSession([AdminLocale::SESSION_KEY => 'en'])
        ->get('/admin')
        ->assertOk()
        ->assertSee('Dashboard')
        ->assertSee('New requests');
});
