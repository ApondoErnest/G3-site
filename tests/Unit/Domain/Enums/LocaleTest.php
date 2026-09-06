<?php

use App\Domain\Enums\Locale;

test('locale enum round-trips database values', function () {
    expect(Locale::Fr->value)->toBe('fr')
        ->and(Locale::En->value)->toBe('en');
});

test('locale fromString falls back to french BR-COMP-002', function () {
    expect(Locale::fromString('en'))->toBe(Locale::En)
        ->and(Locale::fromString('de'))->toBe(Locale::Fr)
        ->and(Locale::Fr->fallback())->toBe(Locale::Fr);
});
