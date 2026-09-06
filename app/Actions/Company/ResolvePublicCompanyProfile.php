<?php

namespace App\Actions\Company;

use App\Actions\Company\Data\PublicCompanyProfile;
use App\Domain\Enums\Locale;
use App\Settings\CompanySettings;

final class ResolvePublicCompanyProfile
{
    public function __construct(
        private CompanySettings $settings,
    ) {}

    public function __invoke(Locale $locale): PublicCompanyProfile
    {
        return PublicCompanyProfile::fromSettings($this->settings, $locale);
    }
}
