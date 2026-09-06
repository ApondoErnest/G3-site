<?php

namespace App\Actions\Content\Data;

use App\Models\User;

final readonly class UpdateContentBlockData
{
    /**
     * @param  array{fr: array<string, mixed>, en: array<string, mixed>}  $content
     * @param  array{fr: string, en: string}  $localeStatus
     */
    public function __construct(
        public int $contentBlockId,
        public array $content,
        public array $localeStatus,
        public User $actor,
    ) {}
}
