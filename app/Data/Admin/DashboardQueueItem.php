<?php

namespace App\Data\Admin;

final readonly class DashboardQueueItem
{
    public function __construct(
        public int $id,
        public string $reference,
        public string $requester,
        public string $centreName,
        public string $statusLabel,
        public string $statusClass,
    ) {}
}
