<?php

use App\Domain\Enums\Locale;
use App\Domain\ValueObjects\TranslatableCopy;

test('translatable copy requires both french and english BR-LANG-001', function () {
    $copy = new TranslatableCopy(
        fr: 'Sécurité. Simplicité. Confiance.',
        en: 'Safety. Simplicity. Trust.',
    );

    expect($copy->for(Locale::Fr))->toBe('Sécurité. Simplicité. Confiance.')
        ->and($copy->for(Locale::En))->toBe('Safety. Simplicity. Trust.')
        ->and($copy->toArray())->toBe([
            'fr' => 'Sécurité. Simplicité. Confiance.',
            'en' => 'Safety. Simplicity. Trust.',
        ]);
});

test('translatable copy rejects incomplete arrays', function () {
    expect(fn () => TranslatableCopy::fromArray(['fr' => 'Bonjour']))
        ->toThrow(InvalidArgumentException::class);

    expect(fn () => new TranslatableCopy(fr: 'Bonjour', en: ''))
        ->toThrow(InvalidArgumentException::class);
});
