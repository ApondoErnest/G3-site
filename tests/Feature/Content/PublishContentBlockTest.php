<?php

use App\Actions\Content\Data\PublishContentBlockData;
use App\Actions\Content\Data\UnpublishContentBlockData;
use App\Actions\Content\Data\UpdateContentBlockData;
use App\Actions\Content\PublishContentBlock;
use App\Actions\Content\ResolvePublishedContentBlock;
use App\Actions\Content\UnpublishContentBlock;
use App\Actions\Content\UpdateContentBlock;
use App\Models\Content\ContentBlock;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
    Role::create(['name' => 'content_editor', 'guard_name' => 'web']);
    Role::create(['name' => 'reception_officer', 'guard_name' => 'web']);
});

test('publish content block requires both locales complete BR-LANG-001', function () {
    $user = User::factory()->create();
    $user->assignRole('content_editor');

    $blockId = insertContentBlock([
        'key' => 'home.hero',
        'locale_status' => json_encode(['fr' => 'complete', 'en' => 'incomplete']),
    ]);

    expect(fn () => app(PublishContentBlock::class)(new PublishContentBlockData(
        contentBlockId: $blockId,
        actor: $user,
    )))->toThrow(InvalidArgumentException::class);
});

test('publish content block succeeds when both locales are complete', function () {
    $user = User::factory()->create();
    $user->assignRole('content_editor');

    $blockId = insertContentBlock([
        'key' => 'home.hero',
        'locale_status' => json_encode(['fr' => 'complete', 'en' => 'complete']),
    ]);

    $block = app(PublishContentBlock::class)(new PublishContentBlockData(
        contentBlockId: $blockId,
        actor: $user,
    ));

    expect($block->is_published)->toBeTrue()
        ->and($block->published_at)->not->toBeNull()
        ->and(app(ResolvePublishedContentBlock::class)('home.hero'))->not->toBeNull();
});

test('update content block auto unpublishes when locale completeness drops', function () {
    $user = User::factory()->create();
    $user->assignRole('content_editor');

    $blockId = insertContentBlock([
        'key' => 'home.hero',
        'is_published' => true,
        'published_at' => now(),
        'locale_status' => json_encode(['fr' => 'complete', 'en' => 'complete']),
    ]);

    app(UpdateContentBlock::class)(new UpdateContentBlockData(
        contentBlockId: $blockId,
        content: [
            'fr' => ['headline' => 'Bienvenue'],
            'en' => ['headline' => ''],
        ],
        localeStatus: ['fr' => 'complete', 'en' => 'incomplete'],
        actor: $user,
    ));

    expect(DB::table('content_blocks')->where('id', $blockId)->value('is_published'))->toBe(0)
        ->and(app(ResolvePublishedContentBlock::class)('home.hero'))->toBeNull();
});

test('unpublish content block removes block from public resolver', function () {
    $user = User::factory()->create();
    $user->assignRole('content_editor');

    $blockId = insertContentBlock([
        'key' => 'home.hero',
        'is_published' => true,
        'published_at' => now(),
    ]);

    app(UnpublishContentBlock::class)(new UnpublishContentBlockData(
        contentBlockId: $blockId,
        actor: $user,
    ));

    expect(app(ResolvePublishedContentBlock::class)('home.hero'))->toBeNull();
});

test('reception officer cannot publish content blocks FR-AD-03', function () {
    $user = User::factory()->create();
    $user->assignRole('reception_officer');

    $blockId = insertContentBlock([
        'locale_status' => json_encode(['fr' => 'complete', 'en' => 'complete']),
    ]);

    expect(fn () => app(PublishContentBlock::class)(new PublishContentBlockData(
        contentBlockId: $blockId,
        actor: $user,
    )))->toThrow(AuthorizationException::class);
});

test('content block isReadyToPublish reflects locale status', function () {
    $completeId = insertContentBlock([
        'locale_status' => json_encode(['fr' => 'complete', 'en' => 'complete']),
    ]);
    $incompleteId = insertContentBlock([
        'locale_status' => json_encode(['fr' => 'complete', 'en' => 'incomplete']),
    ]);

    $complete = ContentBlock::query()->find($completeId);
    $incomplete = ContentBlock::query()->find($incompleteId);

    expect($complete->isReadyToPublish())->toBeTrue()
        ->and($incomplete->isReadyToPublish())->toBeFalse();
});
