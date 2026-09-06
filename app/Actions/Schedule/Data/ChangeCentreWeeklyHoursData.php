<?php

namespace App\Actions\Schedule\Data;

use App\Models\User;

final readonly class ChangeCentreWeeklyHoursData
{
    /**
     * @param  list<WeeklyHoursData>  $weeklyHours
     */
    public function __construct(
        public int $centreId,
        public array $weeklyHours,
        public User $actor,
    ) {}
}
