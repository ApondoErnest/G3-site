<?php

namespace App\Data\Admin;

final readonly class DashboardExceptionSummary
{
    public function __construct(
        public string $label,
        public string $detail,
    ) {}
}
