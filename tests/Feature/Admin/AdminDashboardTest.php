<?php

use App\Models\Centre\Centre;
use App\Support\Clock;
use Carbon\CarbonImmutable;
use Database\Seeders\BaselineCentresSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['admin.mfa_required' => false]);
    seedAdminRoles();
    $this->seed(BaselineCentresSeeder::class);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    Clock::freeze(CarbonImmutable::parse('2026-09-04 10:00:00', 'Africa/Douala'));
});

afterEach(function (): void {
    Clock::unfreeze();
});

test('super admin dashboard shows stats queue and centre status FR-AD-04', function (): void {
    $centres = Centre::query()->orderBy('sort_order')->get();
    $ecoleId = $centres[0]->id;
    $nomayosId = $centres[1]->id;

    insertAppointmentRequest([
        'centre_id' => $ecoleId,
        'status' => 'received',
        'created_at' => now(),
    ]);
    insertAppointmentRequest([
        'centre_id' => $nomayosId,
        'status' => 'received',
        'created_at' => now(),
    ]);
    insertAppointmentRequest([
        'centre_id' => $ecoleId,
        'status' => 'under_review',
        'created_at' => now()->subDay(),
    ]);
    insertContactMessage(['status' => 'new']);

    $user = createAdminUser('super_admin');

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Nouvelles demandes')
        ->assertSee('Statut des centres')
        ->assertSee('File des demandes')
        ->assertSee('École de Police')
        ->assertSee('Nomayos')
        ->assertSee('Tableau de bord')
        ->assertSee('Yaoundé')
        ->assertSee('En traitement');
});

test('dashboard queue lists priority appointment references', function (): void {
    $centreId = Centre::query()->value('id');

    insertAppointmentRequest([
        'centre_id' => $centreId,
        'public_reference' => 'G3-26-TEST1',
        'status' => 'received',
        'contact_name' => 'Jean Martin',
        'contact_phone_e164' => '+237687187516',
    ]);

    $user = createAdminUser('super_admin');

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertSee('G3-26-TEST1')
        ->assertSee('Jean M.')
        ->assertSee('687…516');
});

test('centre manager dashboard is scoped to assigned centres', function (): void {
    $centres = Centre::query()->orderBy('sort_order')->get();
    $scopedCentre = $centres[0];

    insertAppointmentRequest([
        'centre_id' => $scopedCentre->id,
        'public_reference' => 'G3-26-SCOPD',
        'status' => 'received',
    ]);
    insertAppointmentRequest([
        'centre_id' => $centres[1]->id,
        'public_reference' => 'G3-26-HIDDEN',
        'status' => 'received',
    ]);

    $user = createAdminUser('centre_manager');
    DB::table('admin_user_scopes')->insert([
        'user_id' => $user->id,
        'centre_id' => $scopedCentre->id,
        'created_at' => now(),
    ]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertSee('G3-26-SCOPD')
        ->assertDontSee('G3-26-HIDDEN');
});

test('dashboard shows display timezone in subheading', function (): void {
    $user = createAdminUser('super_admin');

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Yaoundé');
});
