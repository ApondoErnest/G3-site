<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CompanySettings extends Settings
{
    /** Q-08 — nullable until legal counsel confirms */
    public ?string $legal_name;

    public string $display_name;

    public array $slogan;

    /** Company-wide agrément — never per centre (BR-COMP-001). */
    public string $agrement_number;

    public int $agrement_year;

    public string $email;

    public ?string $secondary_email;

    public string $postal_address;

    public array $default_seo_title;

    public array $default_seo_description;

    public array $social_links;

    public static function group(): string
    {
        return 'company';
    }

    /**
     * @return list<string>
     */
    public function contactEmails(): array
    {
        $emails = [$this->email];

        if (filled($this->secondary_email) && $this->secondary_email !== $this->email) {
            $emails[] = $this->secondary_email;
        }

        return $emails;
    }

    public function sloganFor(string $locale): string
    {
        return $this->slogan[$locale] ?? $this->slogan['fr'];
    }

    public function defaultSeoTitleFor(string $locale): string
    {
        return $this->default_seo_title[$locale] ?? $this->default_seo_title['fr'];
    }

    public function defaultSeoDescriptionFor(string $locale): string
    {
        return $this->default_seo_description[$locale] ?? $this->default_seo_description['fr'];
    }

    /** Formatted company-wide agrément label (BR-COMP-001). */
    public function agrementLabel(): string
    {
        return 'N°'.$this->agrement_number;
    }
}
