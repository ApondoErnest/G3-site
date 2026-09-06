<?php

use App\Domain\ValueObjects\TimeWindow;
use App\Support\Clock;
use Carbon\CarbonImmutable;

test('time window treats close instant as closed per BR-TIME-002', function () {
    $window = TimeWindow::forDate('2026-09-09', '07:00:00', '20:00:00', Clock::displayTimezone());

    $openAtSeven = CarbonImmutable::parse('2026-09-09 07:00:00', Clock::displayTimezone());
    $beforeClose = CarbonImmutable::parse('2026-09-09 19:59:00', Clock::displayTimezone());
    $atClose = CarbonImmutable::parse('2026-09-09 20:00:00', Clock::displayTimezone());

    expect($window->contains($openAtSeven))->toBeTrue()
        ->and($window->contains($beforeClose))->toBeTrue()
        ->and($window->contains($atClose))->toBeFalse();
});

test('time window rejects invalid ranges', function () {
    expect(fn () => new TimeWindow(
        CarbonImmutable::parse('2026-09-09 20:00:00', Clock::displayTimezone()),
        CarbonImmutable::parse('2026-09-09 07:00:00', Clock::displayTimezone()),
    ))->toThrow(InvalidArgumentException::class);
});
