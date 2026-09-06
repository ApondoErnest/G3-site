<?php

use App\Actions\Schedule\ChangeCentreWeeklyHours;
use App\Actions\Schedule\Data\ChangeCentreWeeklyHoursData;
use App\Actions\Schedule\Data\WeeklyHoursData;
use App\Domain\Enums\Weekday;
use App\Models\Identity\AdminUserScope;
use App\Models\User;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(BaselineCentresSeeder::class);
});

test('operations admin can change centre weekly hours', function () {
    Role::create(['name' => 'operations_admin', 'guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole('operations_admin');

    $hours = collect(Weekday::cases())->map(fn (Weekday $weekday) => new WeeklyHoursData(
        weekday: $weekday,
        isOpen: true,
        opensAt: '08:00:00',
        closesAt: '18:00:00',
    ))->all();

    app(ChangeCentreWeeklyHours::class)(new ChangeCentreWeeklyHoursData(
        centreId: centreId('nomayos'),
        weeklyHours: $hours,
        actor: $user,
    ));

    expect(DB::table('centre_weekly_hours')
        ->where('centre_id', centreId('nomayos'))
        ->where('weekday', Weekday::Monday->value)
        ->value('opens_at'))->toBe('08:00:00');
});

test('centre manager scoped to ecole cannot edit nomayos hours', function () {
    Role::create(['name' => 'centre_manager', 'guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole('centre_manager');

    AdminUserScope::query()->create([
        'user_id' => $user->id,
        'centre_id' => centreId('ecole-de-police'),
        'created_at' => now(),
    ]);

    $hours = [new WeeklyHoursData(
        weekday: Weekday::Monday,
        isOpen: true,
        opensAt: '08:00:00',
        closesAt: '18:00:00',
    )];

    expect(fn () => app(ChangeCentreWeeklyHours::class)(new ChangeCentreWeeklyHoursData(
        centreId: centreId('nomayos'),
        weeklyHours: $hours,
        actor: $user,
    )))->toThrow(AuthorizationException::class);
});
