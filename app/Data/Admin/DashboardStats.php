<?php

namespace App\Data\Admin;

final readonly class DashboardStats
{
    public function __construct(
        public int $newRequestsToday,
        public string $newRequestsMeta,
        public int $inProcessing,
        public string $inProcessingMeta,
        public int $centresOpen,
        public int $centresTotal,
        public string $centresMeta,
        public int $unhandledMessages,
        public string $messagesMeta,
    ) {}
}
