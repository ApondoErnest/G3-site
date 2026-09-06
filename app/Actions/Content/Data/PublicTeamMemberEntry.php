<?php

namespace App\Actions\Content\Data;

final readonly class PublicTeamMemberEntry
{
    /**
     * @param  array{fr: string, en: string}  $roleTitle
     * @param  array{fr: string, en: string}|null  $bio
     */
    public function __construct(
        public int $id,
        public string $name,
        public array $roleTitle,
        public ?array $bio,
    ) {}
}
