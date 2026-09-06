<?php

namespace App\Actions\Content\Data;

use App\Models\User;

final readonly class UnpublishContentBlockData
{
    public function __construct(
        public int $contentBlockId,
        public User $actor,
    ) {}
}
