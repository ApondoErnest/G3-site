<?php

namespace App\Actions\Company\Data;

use App\Domain\ValueObjects\TranslatableCopy;

final readonly class UpdateCompanySettingsData
{
    /**
     * @param  array<string, string|null>  $socialLinks
     */
    public function __construct(
        public ?string $legalName,
        public string $displayName,
        public TranslatableCopy $slogan,
        public string $agrementNumber,
        public int $agrementYear,
        public string $email,
        public ?string $secondaryEmail,
        public string $postalAddress,
        public TranslatableCopy $defaultSeoTitle,
        public TranslatableCopy $defaultSeoDescription,
        public array $socialLinks,
    ) {}
}
