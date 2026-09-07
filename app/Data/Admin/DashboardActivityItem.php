<?php

namespace App\Data\Admin;

final readonly class DashboardActivityItem
{
    public function __construct(
        public string $title,
        public string $meta,
        public bool $isDone,
    ) {}
}
