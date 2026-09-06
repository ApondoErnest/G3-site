<?php

use App\Settings\CompanySettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('company settings loads baseline defaults from docs/01-baseline.md', function () {
    $settings = app(CompanySettings::class);

    expect($settings->display_name)->toBe('G3 Control')
        ->and($settings->legal_name)->toBeNull()
        ->and($settings->agrement_number)->toBe('0291')
        ->and($settings->agrement_year)->toBe(2020)
        ->and($settings->email)->toBe('g3sarl1@gmail.com')
        ->and($settings->postal_address)->toBe('BP 12775 Yaoundé')
        ->and($settings->slogan)->toBe([
            'fr' => 'Sécurité. Simplicité. Confiance.',
            'en' => 'Safety. Simplicity. Trust.',
        ])
        ->and($settings->sloganFor('en'))->toBe('Safety. Simplicity. Trust.')
        ->and($settings->social_links)->toBe([]);
});

test('agrement is stored company-wide in settings group not per centre BR-COMP-001', function () {
    $settings = app(CompanySettings::class);

    expect(CompanySettings::group())->toBe('company')
        ->and($settings->agrement_number)->toBe('0291');
});

test('company settings persists updates', function () {
    $settings = app(CompanySettings::class);

    $settings->display_name = 'G3 Control SARL';
    $settings->legal_name = 'G3 Control SARL';
    $settings->save();

    $reloaded = app(CompanySettings::class);

    expect($reloaded->display_name)->toBe('G3 Control SARL')
        ->and($reloaded->legal_name)->toBe('G3 Control SARL');
});
