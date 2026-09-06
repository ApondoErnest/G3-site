<?php

use App\Actions\Content\ResolveEquipmentForCentre;
use App\Actions\Content\ResolvePageSeo;
use App\Actions\Content\ResolvePublicTeamMembers;
use App\Actions\Content\ResolvePublishedContentBlock;
use App\Actions\Content\ResolvePublishedContentBlocksForPage;
use App\Actions\Content\ResolvePublishedFaqEntries;
use App\Actions\Content\ResolvePublishedRoadSafetySections;
use App\Domain\Enums\ContentPage;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
    $this->seed(BaselineCentresSeeder::class);
});

test('resolve published content block returns only published blocks', function () {
    insertContentBlock([
        'key' => 'home.hero',
        'is_published' => true,
        'published_at' => now(),
    ]);
    insertContentBlock([
        'key' => 'home.proof',
        'is_published' => false,
    ]);

    expect(app(ResolvePublishedContentBlock::class)('home.hero'))->not->toBeNull()
        ->and(app(ResolvePublishedContentBlock::class)('home.proof'))->toBeNull();
});

test('resolve published content blocks for page returns published blocks only', function () {
    insertContentBlock([
        'key' => 'home.hero',
        'page' => 'home',
        'is_published' => true,
        'published_at' => now(),
    ]);
    insertContentBlock([
        'key' => 'home.proof',
        'page' => 'home',
        'is_published' => false,
    ]);
    insertContentBlock([
        'key' => 'about.mission',
        'page' => 'about',
        'is_published' => true,
        'published_at' => now(),
    ]);

    $homeBlocks = app(ResolvePublishedContentBlocksForPage::class)(ContentPage::Home);

    expect($homeBlocks)->toHaveCount(1)
        ->and($homeBlocks[0]->key)->toBe('home.hero');
});

test('resolve published faq entries excludes unpublished items', function () {
    insertFaqEntry(['category_code' => 'general', 'is_published' => true]);
    insertFaqEntry(['category_code' => 'general', 'is_published' => false]);

    expect(app(ResolvePublishedFaqEntries::class)())->toHaveCount(1)
        ->and(app(ResolvePublishedFaqEntries::class)('general'))->toHaveCount(1)
        ->and(app(ResolvePublishedFaqEntries::class)('tariffs'))->toHaveCount(0);
});

test('resolve public team members excludes hidden profiles FR-CN-05', function () {
    insertTeamMember(['display_publicly' => true, 'sort_order' => 1]);
    insertTeamMember(['display_publicly' => false, 'sort_order' => 2, 'name' => 'Hidden Member']);

    $members = app(ResolvePublicTeamMembers::class)();

    expect($members)->toHaveCount(1)
        ->and($members[0]->name)->toBe('Jean Dupont');
});

test('resolve published road safety sections respects sort order FR-CN-03', function () {
    insertRoadSafetySection([
        'anchor' => 'tyres',
        'sort_order' => 2,
        'is_published' => true,
    ]);
    insertRoadSafetySection([
        'anchor' => 'braking',
        'sort_order' => 1,
        'is_published' => true,
    ]);
    insertRoadSafetySection([
        'anchor' => 'draft',
        'sort_order' => 3,
        'is_published' => false,
    ]);

    $sections = app(ResolvePublishedRoadSafetySections::class)();

    expect($sections)->toHaveCount(2)
        ->and($sections[0]->anchor)->toBe('braking')
        ->and($sections[1]->anchor)->toBe('tyres');
});

test('resolve equipment for centre returns linked equipment FR-CN-06', function () {
    $centreId = centreId('ecole-de-police');
    $otherCentreId = centreId('nomayos');
    $sharedEquipmentId = insertEquipment(['code' => 'brake-tester']);
    $ecoleOnlyId = insertEquipment(['code' => 'headlight-tester']);

    linkEquipmentToCentre($sharedEquipmentId, $centreId);
    linkEquipmentToCentre($sharedEquipmentId, $otherCentreId);
    linkEquipmentToCentre($ecoleOnlyId, $centreId);

    $equipment = app(ResolveEquipmentForCentre::class)($centreId);

    expect($equipment)->toHaveCount(2)
        ->and(collect($equipment)->pluck('code')->all())->toContain('brake-tester', 'headlight-tester');
});

test('resolve page seo returns bilingual metadata', function () {
    insertPageSeo(['page' => 'home']);

    $seo = app(ResolvePageSeo::class)(ContentPage::Home);

    expect($seo)->not->toBeNull()
        ->and($seo->seoTitle['fr'])->toBe('Accueil')
        ->and($seo->seoDescription['en'])->toBe('Description EN');
});

test('published content block query is cached', function () {
    insertContentBlock([
        'key' => 'home.hero',
        'is_published' => true,
        'published_at' => now(),
    ]);

    app(ResolvePublishedContentBlock::class)('home.hero');

    DB::table('content_blocks')->where('key', 'home.hero')->delete();

    expect(app(ResolvePublishedContentBlock::class)('home.hero'))->not->toBeNull();
});
