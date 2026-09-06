<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    private const PROPERTIES = [
        'legal_name',
        'display_name',
        'slogan',
        'agrement_number',
        'agrement_year',
        'email',
        'postal_address',
        'default_seo_title',
        'default_seo_description',
        'social_links',
    ];

    public function up(): void
    {
        $this->migrator->add('company.legal_name', null);
        $this->migrator->add('company.display_name', 'G3 Control');
        $this->migrator->add('company.slogan', [
            'fr' => 'Sécurité. Simplicité. Confiance.',
            'en' => 'Safety. Simplicity. Trust.',
        ]);
        $this->migrator->add('company.agrement_number', '0291');
        $this->migrator->add('company.agrement_year', 2020);
        $this->migrator->add('company.email', 'g3sarl1@gmail.com');
        $this->migrator->add('company.postal_address', 'BP 12775 Yaoundé');
        $this->migrator->add('company.default_seo_title', [
            'fr' => 'G3 Control — Visite technique à Yaoundé',
            'en' => 'G3 Control — Technical inspection in Yaoundé',
        ]);
        $this->migrator->add('company.default_seo_description', [
            'fr' => 'Deux centres. 7 jours sur 7. Une même exigence de sécurité.',
            'en' => 'Two centres. 7 days a week. The same safety standard.',
        ]);
        $this->migrator->add('company.social_links', []);
    }

    public function down(): void
    {
        foreach (self::PROPERTIES as $property) {
            $this->migrator->deleteIfExists("company.{$property}");
        }
    }
};
