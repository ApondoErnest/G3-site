<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(BaselineCentresSeeder::class);
        $this->call(OfficialTariffsSeeder::class);
        $this->call(PublicAdminBaselineSeeder::class);
        $this->call(IngestRealMediaSeeder::class);
    }
}
