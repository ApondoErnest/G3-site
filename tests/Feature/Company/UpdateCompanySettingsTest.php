<?php

use App\Actions\Company\Data\UpdateCompanySettingsData;
use App\Actions\Company\UpdateCompanySettings;
use App\Domain\ValueObjects\TranslatableCopy;
use App\Models\User;
use App\Settings\CompanySettings;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

function companySettingsPayload(array $overrides = []): UpdateCompanySettingsData
{
    return new UpdateCompanySettingsData(
        legalName: $overrides['legalName'] ?? 'G3 Control SARL',
        displayName: $overrides['displayName'] ?? 'G3 Control',
        slogan: $overrides['slogan'] ?? new TranslatableCopy(
            fr: 'Sécurité. Simplicité. Confiance.',
            en: 'Safety. Simplicity. Trust.',
        ),
        agrementNumber: $overrides['agrementNumber'] ?? '0291',
        agrementYear: $overrides['agrementYear'] ?? 2020,
        email: $overrides['email'] ?? 'g3sarl1@gmail.com',
        postalAddress: $overrides['postalAddress'] ?? 'BP 12775 Yaoundé',
        defaultSeoTitle: $overrides['defaultSeoTitle'] ?? new TranslatableCopy(
            fr: 'G3 Control — Visite technique à Yaoundé',
            en: 'G3 Control — Technical inspection in Yaoundé',
        ),
        defaultSeoDescription: $overrides['defaultSeoDescription'] ?? new TranslatableCopy(
            fr: 'Deux centres. 7 jours sur 7. Une même exigence de sécurité.',
            en: 'Two centres. 7 days a week. The same safety standard.',
        ),
        socialLinks: $overrides['socialLinks'] ?? [],
    );
}

test('super admin can update company settings', function () {
    Role::create(['name' => 'super_admin', 'guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole('super_admin');

    app(UpdateCompanySettings::class)(
        companySettingsPayload(['displayName' => 'G3 Control SARL']),
        $user,
    );

    $settings = app(CompanySettings::class);

    expect($settings->display_name)->toBe('G3 Control SARL')
        ->and($settings->legal_name)->toBe('G3 Control SARL');
});

test('non super admin cannot update company settings', function () {
    Role::create(['name' => 'reception_officer', 'guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole('reception_officer');

    expect(fn () => app(UpdateCompanySettings::class)(
        companySettingsPayload(),
        $user,
    ))->toThrow(AuthorizationException::class);
});

test('update company settings persists bilingual fields', function () {
    Role::create(['name' => 'super_admin', 'guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole('super_admin');

    app(UpdateCompanySettings::class)(
        companySettingsPayload([
            'slogan' => new TranslatableCopy(
                fr: 'Nouveau slogan.',
                en: 'New slogan.',
            ),
        ]),
        $user,
    );

    $settings = app(CompanySettings::class);

    expect($settings->slogan)->toBe([
        'fr' => 'Nouveau slogan.',
        'en' => 'New slogan.',
    ]);
});
