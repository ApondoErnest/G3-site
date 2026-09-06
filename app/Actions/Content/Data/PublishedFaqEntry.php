<?php

namespace App\Actions\Content\Data;

final readonly class PublishedFaqEntry
{
    /**
     * @param  array{fr: string, en: string}  $question
     * @param  array{fr: string, en: string}  $answer
     */
    public function __construct(
        public int $id,
        public string $categoryCode,
        public array $question,
        public array $answer,
    ) {}
}
