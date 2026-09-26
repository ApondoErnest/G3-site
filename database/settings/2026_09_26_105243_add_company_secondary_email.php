<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('company.secondary_email', 'admin@g3control.com');
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('company.secondary_email');
    }
};
