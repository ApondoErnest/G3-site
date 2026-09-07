<?php

namespace App\Data\Admin;

final readonly class DashboardTariffSummary
{
    public function __construct(
        public string $label,
        public string $effectiveLine,
        public int $itemCount,
    ) {}
}
