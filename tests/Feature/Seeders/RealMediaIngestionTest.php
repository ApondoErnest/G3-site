<?php

use Database\Seeders\BaselineCentresSeeder;
use Database\Seeders\IngestRealMediaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('real media seeder stores the g3 logo and centre photographs only', function () {
    Storage::fake('local');
    $this->seed(BaselineCentresSeeder::class);
    $this->seed(IngestRealMediaSeeder::class);

    expect(DB::table('media')->count())->toBe(20)
        ->and(DB::table('media')->where('collection_name', 'brand')->count())->toBe(2)
        ->and(DB::table('media')->where('collection_name', 'centres')->count())->toBe(18)
        ->and(DB::table('media')->whereIn('collection_name', ['equipment', 'team', 'inspection', 'road_safety'])->count())->toBe(0)
        ->and(DB::table('media')->where('mime_type', 'video/mp4')->count())->toBe(0)
        ->and(DB::table('media')->where('file_name', 'like', '%.svg')->count())->toBe(0)
        ->and(DB::table('media')->where('name', 'taxi')->count())->toBe(0);

    $logo = DB::table('media')->where('name', 'brand_logo_primary')->first();
    $logoProperties = json_decode((string) $logo->custom_properties, true);

    expect($logo->disk)->toBe('local')
        ->and($logo->mime_type)->toBe('image/png')
        ->and($logoProperties['alt']['fr'])->toBe('Logo G3 Control')
        ->and($logoProperties['alt']['en'])->toBe('G3 Control logo')
        ->and($logoProperties['is_published'])->toBeTrue()
        ->and(is_file(public_path('images/reusable/site-logo.png')))->toBeTrue();

    $englishAlt = DB::table('media')->get()
        ->map(fn (object $media): string => (string) (json_decode((string) $media->custom_properties, true)['alt']['en'] ?? ''))
        ->implode("\n");

    expect($englishAlt)
        ->not->toContain('Center')
        ->not->toContain('center')
        ->not->toContain('License')
        ->not->toContain('MOT');

    DB::table('media')->get()->each(function (object $media): void {
        $properties = json_decode((string) $media->custom_properties, true);

        expect($properties['alt']['fr'])->not->toBe('')
            ->and($properties['alt']['en'])->not->toBe('')
            ->and($properties['is_published'])->toBeTrue()
            ->and($media->mime_type)->toBe('image/png');
    });
});

test('real media seeder is idempotent', function () {
    Storage::fake('local');
    $this->seed(BaselineCentresSeeder::class);
    $this->seed(IngestRealMediaSeeder::class);
    $this->seed(IngestRealMediaSeeder::class);

    expect(DB::table('media')->count())->toBe(20)
        ->and(DB::table('brand_assets')->count())->toBe(1);
});
