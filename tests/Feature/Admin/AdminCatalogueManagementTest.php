<?php

use App\Filament\Resources\Catalogue\RequiredDocuments\RequiredDocuments\RequiredDocumentResource;
use App\Filament\Resources\Catalogue\Services\Services\Pages\CreateService;
use App\Filament\Resources\Catalogue\Services\Services\ServiceResource;
use App\Filament\Resources\Catalogue\VehicleCategories\VehicleCategories\VehicleCategoryResource;
use App\Models\Catalogue\RequiredDocument;
use App\Models\Catalogue\Service;
use App\Models\Catalogue\VehicleCategory;
use App\Support\CacheKeys;
use Database\Seeders\BaselineCentresSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['admin.mfa_required' => false]);
    seedAdminRoles();
    $this->seed(BaselineCentresSeeder::class);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

test('content editor can access catalogue management pages', function (): void {
    $user = createAdminUser('content_editor');

    $this->actingAs($user)
        ->get(ServiceResource::getUrl('index'))
        ->assertOk()
        ->assertSee(__('admin.catalogue.services.navigation', locale: 'fr'));

    $this->actingAs($user)
        ->get(VehicleCategoryResource::getUrl('index'))
        ->assertOk()
        ->assertSee(__('admin.catalogue.categories.navigation', locale: 'fr'));

    $this->actingAs($user)
        ->get(RequiredDocumentResource::getUrl('index'))
        ->assertOk()
        ->assertSee(__('admin.catalogue.documents.navigation', locale: 'fr'));
});

test('operations admin can view catalogue but cannot create services', function (): void {
    $user = createAdminUser('operations_admin');

    $this->actingAs($user)
        ->get(ServiceResource::getUrl('index'))
        ->assertOk();

    $this->actingAs($user)
        ->get(ServiceResource::getUrl('create'))
        ->assertForbidden();

    expect($user->can('viewAny', Service::class))->toBeTrue()
        ->and($user->can('create', Service::class))->toBeFalse();
});

test('reception officer cannot access catalogue admin', function (): void {
    $user = createAdminUser('reception_officer');

    $this->actingAs($user)
        ->get(ServiceResource::getUrl('index'))
        ->assertForbidden();
});

test('creating service clears published catalogue cache', function (): void {
    Cache::put(CacheKeys::catalogueServices(), ['cached' => true], 3600);
    Cache::put(CacheKeys::catalogueCategories(), ['cached' => true], 3600);

    $centreId = centreId('ecole-de-police');
    $user = createAdminUser('content_editor');

    Livewire::actingAs($user)
        ->test(CreateService::class)
        ->fillForm([
            'code' => 'visite-technique',
            'title' => [
                'fr' => 'Visite technique',
                'en' => 'Technical inspection',
            ],
            'summary' => ['fr' => '', 'en' => ''],
            'body' => ['fr' => '', 'en' => ''],
            'sort_order' => 1,
            'is_published' => true,
            'centres' => [$centreId],
            'vehicleCategories' => [],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Cache::has(CacheKeys::catalogueServices()))->toBeFalse()
        ->and(Cache::has(CacheKeys::catalogueCategories()))->toBeFalse()
        ->and(Service::query()->where('code', 'visite-technique')->exists())->toBeTrue();
});

test('catalogue policies restrict write access to content editors FR-AD-03', function (): void {
    $editor = createAdminUser('content_editor');
    $ops = createAdminUser('operations_admin');

    $service = Service::query()->create([
        'code' => 'test-service',
        'title' => ['fr' => 'Service test', 'en' => 'Test service'],
        'summary' => null,
        'body' => null,
        'icon' => null,
        'sort_order' => 1,
        'is_published' => false,
    ]);

    $category = VehicleCategory::query()->create([
        'code' => 'vp',
        'label' => ['fr' => 'VP', 'en' => 'PC'],
        'examples' => null,
        'description' => null,
        'sort_order' => 1,
        'is_published' => false,
    ]);

    $document = RequiredDocument::query()->create([
        'label' => ['fr' => 'Carte grise', 'en' => 'Registration card'],
        'service_id' => $service->id,
        'vehicle_category_id' => null,
        'sort_order' => 1,
    ]);

    expect($editor->can('create', Service::class))->toBeTrue()
        ->and($editor->can('update', $service))->toBeTrue()
        ->and($ops->can('viewAny', Service::class))->toBeTrue()
        ->and($ops->can('update', $service))->toBeFalse()
        ->and($editor->can('update', $category))->toBeTrue()
        ->and($editor->can('update', $document))->toBeTrue();
});
