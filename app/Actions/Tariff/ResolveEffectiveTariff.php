<?php

namespace App\Actions\Tariff;

use App\Domain\Tariff\EffectiveTariffResult;
use App\Domain\Tariff\TariffResolver;
use App\Domain\ValueObjects\MoneyXaf;
use App\Support\Clock;
use Carbon\CarbonImmutable;

final class ResolveEffectiveTariff
{
    public function __construct(
        private TariffResolver $resolver,
    ) {}

    public function __invoke(
        ?CarbonImmutable $asOfDate = null,
        ?int $centreId = null,
        ?int $vehicleCategoryId = null,
        ?int $serviceId = null,
    ): EffectiveTariffResult {
        return $this->resolver->resolveEffective(
            $asOfDate ?? Clock::nowDisplay(),
            $centreId,
            $vehicleCategoryId,
            $serviceId,
        );
    }

    public function findPrice(
        int $centreId,
        int $vehicleCategoryId,
        ?int $serviceId = null,
        ?CarbonImmutable $asOfDate = null,
    ): ?MoneyXaf {
        return $this->resolver->findPrice(
            $asOfDate ?? Clock::nowDisplay(),
            $centreId,
            $vehicleCategoryId,
            $serviceId,
        );
    }
}
