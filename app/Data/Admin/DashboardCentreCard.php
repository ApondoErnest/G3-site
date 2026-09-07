<?php

namespace App\Data\Admin;

final readonly class DashboardCentreCard
{
    public function __construct(
        public string $name,
        public bool $isOpen,
        public string $statusLabel,
        public string $hoursLine,
    ) {}
}
