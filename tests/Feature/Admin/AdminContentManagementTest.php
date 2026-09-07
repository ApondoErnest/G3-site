<?php

use App\Filament\Pages\AuditLog;
use App\Filament\Pages\ManageMediaLibrary;
use App\Filament\Resources\Content\ContentBlocks\ContentBlocks\ContentBlockResource;
use App\Filament\Resources\Content\FaqEntries\FaqEntries\FaqEntryResource;
use App\Filament\Resources\Content\RoadSafetySections\RoadSafetySections\RoadSafetySectionResource;
use App\Filament\Resources\Content\TeamMembers\TeamMembers\TeamMemberResource;
use App\Filament\Resources\System\PageSeos\PageSeos\PageSeoResource;
use App\Models\Content\FaqEntry;
use App\Models\Content\PageSeo;
use Database\Seeders\BaselineCentresSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['admin.mfa_required' => false]);
    seedAdminRoles();
    $this->seed(BaselineCentresSeeder::class);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

test('content editor can access content management pages', function (): void {
    $user = createAdminUser('content_editor');
    insertContentBlock(['key' => 'home.hero']);
    insertPageSeo(['page' => 'home']);

    $this->actingAs($user)
        ->get(ContentBlockResource::getUrl('index'))
        ->assertOk()
        ->assertSee(__('admin.content.blocks.navigation', locale: 'fr'));

    $this->actingAs($user)
        ->get(FaqEntryResource::getUrl('index'))
        ->assertOk()
        ->assertSee(__('admin.content.faq.navigation', locale: 'fr'));

    $this->actingAs($user)
        ->get(TeamMemberResource::getUrl('index'))
        ->assertOk()
        ->assertSee(__('admin.content.team.navigation', locale: 'fr'));

    $this->actingAs($user)
        ->get(RoadSafetySectionResource::getUrl('index'))
        ->assertOk()
        ->assertSee(__('admin.content.road_safety.navigation', locale: 'fr'));

    $this->actingAs($user)
        ->get(PageSeoResource::getUrl('index'))
        ->assertOk()
        ->assertSee(__('admin.content.page_seo.navigation', locale: 'fr'));

    $this->actingAs($user)
        ->get(ManageMediaLibrary::getUrl())
        ->assertOk()
        ->assertSee(__('admin.media.title', locale: 'fr'));
});

test('operations admin cannot access content management', function (): void {
    $user = createAdminUser('operations_admin');

    $this->actingAs($user)
        ->get(ContentBlockResource::getUrl('index'))
        ->assertForbidden();

    $this->actingAs($user)
        ->get(ManageMediaLibrary::getUrl())
        ->assertForbidden();

    expect($user->can('viewAny', FaqEntry::class))->toBeFalse();
});

test('reception officer cannot access content management', function (): void {
    $user = createAdminUser('reception_officer');

    $this->actingAs($user)
        ->get(ContentBlockResource::getUrl('index'))
        ->assertForbidden();
});

test('content editor can edit page seo but cannot create records', function (): void {
    insertPageSeo(['page' => 'home']);
    $user = createAdminUser('content_editor');
    $pageSeo = PageSeo::query()->findOrFail('home');

    expect($user->can('viewAny', PageSeo::class))->toBeTrue()
        ->and($user->can('update', $pageSeo))->toBeTrue()
        ->and($user->can('create', PageSeo::class))->toBeFalse();

    $this->actingAs($user)
        ->get(PageSeoResource::getUrl('edit', ['record' => 'home']))
        ->assertOk();
});
