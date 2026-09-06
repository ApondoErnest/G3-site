<?php

use App\Actions\Schedule\CreateScheduleException;
use App\Actions\Schedule\Data\CreateScheduleExceptionData;
use App\Actions\Schedule\ResolveAllCentresAvailability;
use App\Actions\Schedule\ResolveCentreAvailability;
use App\Domain\Enums\LiveCentreState;
use App\Domain\Enums\PreferredPeriod;
use App\Models\User;
use App\Support\Clock;
use Carbon\CarbonImmutable;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(BaselineCentresSeeder::class);
});

test('monday 10:00 both centres open with correct next close FR-CE-07', function () {
    $at = freezeDisplayTime('2026-09-07 10:00:00');

    $ecole = app(ResolveCentreAvailability::class)->snapshot(centreId('ecole-de-police'), $at);
    $nomayos = app(ResolveCentreAvailability::class)->snapshot(centreId('nomayos'), $at);

    expect($ecole->isOpenNow)->toBeTrue()
        ->and($ecole->state)->toBe(LiveCentreState::OpenNormalHours)
        ->and($ecole->nextCloseAt?->format('H:i'))->toBe('20:00')
        ->and($nomayos->isOpenNow)->toBeTrue()
        ->and($nomayos->nextCloseAt?->format('H:i'))->toBe('19:00');
});

test('monday 06:59 both closed and 07:00 both open per BR-TIME-002', function () {
    $beforeOpen = freezeDisplayTime('2026-09-07 06:59:00');

    $ecoleBefore = app(ResolveCentreAvailability::class)->snapshot(centreId('ecole-de-police'), $beforeOpen);
    $nomayosBefore = app(ResolveCentreAvailability::class)->snapshot(centreId('nomayos'), $beforeOpen);

    expect($ecoleBefore->isOpenNow)->toBeFalse()
        ->and($ecoleBefore->nextOpenAt?->format('Y-m-d H:i'))->toBe('2026-09-07 07:00')
        ->and($nomayosBefore->isOpenNow)->toBeFalse()
        ->and($nomayosBefore->nextOpenAt?->format('Y-m-d H:i'))->toBe('2026-09-07 07:00');

    Clock::unfreeze();
    $atOpen = freezeDisplayTime('2026-09-07 07:00:00');

    $ecoleOpen = app(ResolveCentreAvailability::class)->snapshot(centreId('ecole-de-police'), $atOpen);
    $nomayosOpen = app(ResolveCentreAvailability::class)->snapshot(centreId('nomayos'), $atOpen);

    expect($ecoleOpen->isOpenNow)->toBeTrue()
        ->and($nomayosOpen->isOpenNow)->toBeTrue();
});

test('sunday 15:00 both closed with next open monday 07:00', function () {
    $at = freezeDisplayTime('2026-09-06 15:00:00');

    $ecole = app(ResolveCentreAvailability::class)->snapshot(centreId('ecole-de-police'), $at);
    $nomayos = app(ResolveCentreAvailability::class)->snapshot(centreId('nomayos'), $at);

    expect($ecole->isOpenNow)->toBeFalse()
        ->and($nomayos->isOpenNow)->toBeFalse()
        ->and($ecole->nextOpenAt?->format('Y-m-d H:i'))->toBe('2026-09-07 07:00')
        ->and($nomayos->nextOpenAt?->format('Y-m-d H:i'))->toBe('2026-09-07 07:00');
});

test('ecole wednesday 20:00 and nomayos wednesday 19:00 are closed', function () {
    $ecoleAt = freezeDisplayTime('2026-09-09 20:00:00');
    $ecole = app(ResolveCentreAvailability::class)->snapshot(centreId('ecole-de-police'), $ecoleAt);

    expect($ecole->isOpenNow)->toBeFalse();

    Clock::unfreeze();
    $nomayosAt = freezeDisplayTime('2026-09-09 19:00:00');
    $nomayos = app(ResolveCentreAvailability::class)->snapshot(centreId('nomayos'), $nomayosAt);

    expect($nomayos->isOpenNow)->toBeFalse();
});

test('exception closed all day makes centre unbookable BR-CENT-002', function () {
    Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    app(CreateScheduleException::class)(new CreateScheduleExceptionData(
        appliesToAllCentres: false,
        centreId: centreId('ecole-de-police'),
        startsOn: CarbonImmutable::parse('2026-09-09', 'Africa/Douala'),
        endsOn: null,
        isOpen: false,
        opensAt: null,
        closesAt: null,
        reason: ['fr' => 'Fermeture exceptionnelle', 'en' => 'Exceptional closure'],
        actor: $user,
    ));

    $resolver = app(ResolveCentreAvailability::class);
    $date = CarbonImmutable::parse('2026-09-09', 'Africa/Douala');

    expect($resolver->isBookableOnDate(centreId('ecole-de-police'), $date, PreferredPeriod::Any))
        ->toBeFalse();
});

test('exception 07:00-15:00 overrides wednesday weekly hours', function () {
    Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    app(CreateScheduleException::class)(new CreateScheduleExceptionData(
        appliesToAllCentres: false,
        centreId: centreId('ecole-de-police'),
        startsOn: CarbonImmutable::parse('2026-09-09', 'Africa/Douala'),
        endsOn: null,
        isOpen: true,
        opensAt: '07:00:00',
        closesAt: '15:00:00',
        reason: null,
        actor: $user,
    ));

    $at = freezeDisplayTime('2026-09-09 14:59:00');
    $open = app(ResolveCentreAvailability::class)->snapshot(centreId('ecole-de-police'), $at);

    expect($open->isOpenNow)->toBeTrue()
        ->and($open->nextCloseAt?->format('H:i'))->toBe('15:00');

    Clock::unfreeze();
    $closed = freezeDisplayTime('2026-09-09 15:00:00');
    $snapshot = app(ResolveCentreAvailability::class)->snapshot(centreId('ecole-de-police'), $closed);

    expect($snapshot->isOpenNow)->toBeFalse();
});

test('global closure makes both centres unbookable that date', function () {
    Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    app(CreateScheduleException::class)(new CreateScheduleExceptionData(
        appliesToAllCentres: true,
        centreId: null,
        startsOn: CarbonImmutable::parse('2026-09-09', 'Africa/Douala'),
        endsOn: null,
        isOpen: false,
        opensAt: null,
        closesAt: null,
        reason: null,
        actor: $user,
    ));

    $resolver = app(ResolveCentreAvailability::class);
    $date = CarbonImmutable::parse('2026-09-09', 'Africa/Douala');

    expect($resolver->isBookableOnDate(centreId('ecole-de-police'), $date, PreferredPeriod::Any))
        ->toBeFalse()
        ->and($resolver->isBookableOnDate(centreId('nomayos'), $date, PreferredPeriod::Any))
        ->toBeFalse();
});

test('centre-only exception does not change the other centre', function () {
    Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    app(CreateScheduleException::class)(new CreateScheduleExceptionData(
        appliesToAllCentres: false,
        centreId: centreId('ecole-de-police'),
        startsOn: CarbonImmutable::parse('2026-09-09', 'Africa/Douala'),
        endsOn: null,
        isOpen: false,
        opensAt: null,
        closesAt: null,
        reason: null,
        actor: $user,
    ));

    $at = freezeDisplayTime('2026-09-09 10:00:00');

    $ecole = app(ResolveCentreAvailability::class)->snapshot(centreId('ecole-de-police'), $at);
    $nomayos = app(ResolveCentreAvailability::class)->snapshot(centreId('nomayos'), $at);

    expect($ecole->isOpenNow)->toBeFalse()
        ->and($nomayos->isOpenNow)->toBeTrue();
});

test('preferred period validation matches acceptance scenarios', function () {
    $resolver = app(ResolveCentreAvailability::class);
    $wednesday = CarbonImmutable::parse('2026-09-09', 'Africa/Douala');
    $sunday = CarbonImmutable::parse('2026-09-06', 'Africa/Douala');

    expect($resolver->isBookableOnDate(centreId('ecole-de-police'), $wednesday, PreferredPeriod::Afternoon))
        ->toBeTrue()
        ->and($resolver->isBookableOnDate(centreId('nomayos'), $wednesday, PreferredPeriod::Afternoon))
        ->toBeTrue()
        ->and($resolver->isBookableOnDate(centreId('ecole-de-police'), $sunday, PreferredPeriod::Any))
        ->toBeTrue();
});

test('ecole wednesday 19:30 is open and 20:00 is closed per BR-TIME-002', function () {
    $open = freezeDisplayTime('2026-09-09 19:30:00');
    $snapshotOpen = app(ResolveCentreAvailability::class)->snapshot(centreId('ecole-de-police'), $open);

    expect($snapshotOpen->isOpenNow)->toBeTrue();

    Clock::unfreeze();
    $closed = freezeDisplayTime('2026-09-09 20:00:00');
    $snapshotClosed = app(ResolveCentreAvailability::class)->snapshot(centreId('ecole-de-police'), $closed);

    expect($snapshotClosed->isOpenNow)->toBeFalse();
});

test('same snapshot from single and all-centres resolvers FR-CE-08', function () {
    $at = freezeDisplayTime('2026-09-07 10:00:00');

    $single = app(ResolveCentreAvailability::class)->snapshot(centreId('ecole-de-police'), $at);
    $all = app(ResolveAllCentresAvailability::class)($at);

    expect($all[centreId('ecole-de-police')])->toEqual($single);
});
