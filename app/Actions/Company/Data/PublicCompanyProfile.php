<?php

namespace App\Actions\Company\Data;

use App\Domain\Enums\Locale;
use App\Settings\CompanySettings;

final readonly class PublicCompanyProfile
{
    /**
     * @param  array<string, string|null>  $socialLinks
     */
    public function __construct(
        public string $displayName,
        public ?string $legalName,
        public string $slogan,
        public string $agrementLabel,
        public int $agrementYear,
        public string $email,
        /** @var list<string> */
        public array $emails,
        public string $postalAddress,
        public string $defaultSeoTitle,
        public string $defaultSeoDescription,
        public array $socialLinks,
    ) {}

    public static function fromSettings(CompanySettings $settings, Locale $locale): self
    {
        return new self(
            displayName: $settings->display_name,
            legalName: $settings->legal_name,
            slogan: $settings->sloganFor($locale->value),
            agrementLabel: $settings->agrementLabel(),
            agrementYear: $settings->agrement_year,
            email: $settings->email,
            emails: $settings->contactEmails(),
            postalAddress: $settings->postal_address,
            defaultSeoTitle: $settings->defaultSeoTitleFor($locale->value),
            defaultSeoDescription: $settings->defaultSeoDescriptionFor($locale->value),
            socialLinks: $settings->social_links,
        );
    }
}
