<?php

use App\Actions\Company\ResolvePublicCompanyProfile;
use App\Domain\Enums\Locale;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('resolve public company profile returns baseline facts FR-CO-03', function () {
    $profile = app(ResolvePublicCompanyProfile::class)(Locale::Fr);

    expect($profile->displayName)->toBe('G3 Control')
        ->and($profile->legalName)->toBeNull()
        ->and($profile->slogan)->toBe('Sécurité. Simplicité. Confiance.')
        ->and($profile->agrementLabel)->toBe('N°0291')
        ->and($profile->agrementYear)->toBe(2020)
        ->and($profile->email)->toBe('g3sarl1@gmail.com')
        ->and($profile->postalAddress)->toBe('BP 12775 Yaoundé')
        ->and($profile->defaultSeoTitle)->toBe('G3 Control — Visite technique à Yaoundé')
        ->and($profile->defaultSeoDescription)->toBe('Deux centres. 7 jours sur 7. Une même exigence de sécurité.')
        ->and($profile->socialLinks)->toBe([]);
});

test('resolve public company profile localizes english copy', function () {
    $profile = app(ResolvePublicCompanyProfile::class)(Locale::En);

    expect($profile->slogan)->toBe('Safety. Simplicity. Trust.')
        ->and($profile->defaultSeoTitle)->toBe('G3 Control — Technical inspection in Yaoundé')
        ->and($profile->defaultSeoDescription)->toBe('Two centres. 7 days a week. The same safety standard.');
});

test('agrement label stays company-wide not per centre BR-COMP-001', function () {
    $profile = app(ResolvePublicCompanyProfile::class)(Locale::Fr);

    expect($profile->agrementLabel)->toBe('N°0291');
});
