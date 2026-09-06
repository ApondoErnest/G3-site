<?php

namespace App\Actions\Schedule\Data;

use App\Models\User;

final readonly class DeleteScheduleExceptionData
{
    public function __construct(
        public int $exceptionId,
        public User $actor,
    ) {}
}
