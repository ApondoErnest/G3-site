<?php

namespace App\Actions\Company;

use App\Actions\Company\Data\UpdateCompanySettingsData;
use App\Models\User;
use App\Settings\CompanySettings;
use Illuminate\Auth\Access\AuthorizationException;

final class UpdateCompanySettings
{
    public function __construct(
        private CompanySettings $settings,
    ) {}

    public function __invoke(UpdateCompanySettingsData $data, User $actor): void
    {
        if (! $actor->hasRole('super_admin')) {
            throw new AuthorizationException('Only super administrators may update company settings.');
        }

        $this->settings->legal_name = $data->legalName;
        $this->settings->display_name = $data->displayName;
        $this->settings->slogan = $data->slogan->toArray();
        $this->settings->agrement_number = $data->agrementNumber;
        $this->settings->agrement_year = $data->agrementYear;
        $this->settings->email = $data->email;
        $this->settings->postal_address = $data->postalAddress;
        $this->settings->default_seo_title = $data->defaultSeoTitle->toArray();
        $this->settings->default_seo_description = $data->defaultSeoDescription->toArray();
        $this->settings->social_links = $data->socialLinks;

        $this->settings->save();
    }
}
