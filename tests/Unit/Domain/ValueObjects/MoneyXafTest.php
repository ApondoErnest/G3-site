<?php

use App\Domain\ValueObjects\MoneyXaf;

test('money xaf formats grouped fcfa per BR-TARIFF-002', function () {
    expect((new MoneyXaf(25000))->formatted())->toBe('25 000 FCFA');
});

test('money xaf rejects zero or negative amounts', function () {
    expect(fn () => new MoneyXaf(0))->toThrow(InvalidArgumentException::class);
});
