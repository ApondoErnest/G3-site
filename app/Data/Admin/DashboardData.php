<?php

namespace App\Data\Admin;

final readonly class DashboardData
{
    /**
     * @param  list<DashboardQueueItem>  $queue
     * @param  list<DashboardCentreCard>  $centres
     * @param  list<DashboardActivityItem>  $activity
     */
    public function __construct(
        public string $displayDateLine,
        public ?DashboardStats $stats,
        public array $queue,
        public array $centres,
        public ?DashboardExceptionSummary $nextException,
        public ?DashboardTariffSummary $tariff,
        public array $activity,
    ) {}
}
