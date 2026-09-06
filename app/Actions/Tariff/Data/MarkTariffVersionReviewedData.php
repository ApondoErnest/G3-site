<?php

namespace App\Actions\Tariff\Data;

use App\Models\User;

final readonly class MarkTariffVersionReviewedData
{
    public function __construct(
        public int $tariffVersionId,
        public User $actor,
    ) {}
}
