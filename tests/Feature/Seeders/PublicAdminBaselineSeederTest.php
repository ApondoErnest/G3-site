<?php

use Database\Seeders\BaselineCentresSeeder;
use Database\Seeders\OfficialTariffsSeeder;
use Database\Seeders\PublicAdminBaselineSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('public admin baseline seeder fills admin managed public catalogue and content', function () {
    $this->seed(BaselineCentresSeeder::class);
    $this->seed(OfficialTariffsSeeder::class);
    $this->seed(PublicAdminBaselineSeeder::class);

    expect(DB::table('services')->count())->toBe(6)
        ->and(DB::table('vehicle_categories')->count())->toBe(7)
        ->and(DB::table('required_documents')->count())->toBe(18)
        ->and(DB::table('equipment')->count())->toBe(6)
        ->and(DB::table('centre_equipment')->count())->toBe(12)
        ->and(DB::table('faq_entries')->count())->toBe(4)
        ->and(DB::table('road_safety_sections')->count())->toBe(5)
        ->and(DB::table('page_seo')->count())->toBe(11)
        ->and(DB::table('content_blocks')->count())->toBe(15)
        ->and(DB::table('team_members')->count())->toBe(0);

    expect(DB::table('services')->where('code', 'periodic-technical-inspection')->value('icon'))
        ->toBe('service-periodic.svg');

    expect(DB::table('services')->where('code', 'heavy-vehicle-inspection')->exists())->toBeTrue()
        ->and(DB::table('road_safety_sections')->where('anchor', 'sixty-second-check')->exists())->toBeTrue()
        ->and(DB::table('page_seo')->where('page', 'road_safety')->exists())->toBeTrue();

    $hero = DB::table('content_blocks')->where('key', 'home.hero')->first();
    $status = json_decode((string) $hero->locale_status, true);
    $content = json_decode((string) $hero->content, true);

    expect($hero->is_published)->toBe(1)
        ->and($status['fr'])->toBe('complete')
        ->and($status['en'])->toBe('complete')
        ->and($content['fr']['headline'])->toBe('La sécurité commence par un contrôle rigoureux.')
        ->and($content['en']['headline'])->toBe('Safety starts with a rigorous inspection.')
        ->and($content['en']['body'])->not->toBe('');

    DB::table('content_blocks')->get()->each(function (object $block): void {
        $localeStatus = json_decode((string) $block->locale_status, true);
        $copy = json_decode((string) $block->content, true);

        expect($block->is_published)->toBe(1)
            ->and($block->published_at)->not->toBeNull()
            ->and($localeStatus['fr'])->toBe('complete')
            ->and($localeStatus['en'])->toBe('complete')
            ->and($copy['en']['headline'])->not->toBe('')
            ->and($copy['en']['body'])->not->toBe('');
    });
});

test('public admin baseline seeder is idempotent', function () {
    $this->seed(BaselineCentresSeeder::class);
    $this->seed(OfficialTariffsSeeder::class);
    $this->seed(PublicAdminBaselineSeeder::class);
    $this->seed(PublicAdminBaselineSeeder::class);

    expect(DB::table('services')->count())->toBe(6)
        ->and(DB::table('required_documents')->count())->toBe(18)
        ->and(DB::table('equipment')->count())->toBe(6)
        ->and(DB::table('centre_equipment')->count())->toBe(12)
        ->and(DB::table('faq_entries')->count())->toBe(4)
        ->and(DB::table('road_safety_sections')->count())->toBe(5)
        ->and(DB::table('page_seo')->count())->toBe(11)
        ->and(DB::table('content_blocks')->count())->toBe(15);
});
