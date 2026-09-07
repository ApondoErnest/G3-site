<?php

namespace App\Filament\Resources\System\PageSeos\PageSeos\Pages;

use App\Filament\Resources\System\PageSeos\PageSeos\PageSeoResource;
use App\Models\Content\PageSeo;
use App\Support\CacheKeys;
use App\Support\Filament\AdminForm;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditPageSeo extends EditRecord
{
    protected static string $resource = PageSeoResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['seo_title'] = AdminForm::bilingualState($data['seo_title'] ?? null);
        $data['seo_description'] = AdminForm::bilingualState($data['seo_description'] ?? null);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['seo_title'] = AdminForm::normalizeBilingual($data['seo_title'] ?? null);
        $data['seo_description'] = AdminForm::normalizeBilingual($data['seo_description'] ?? null);
        $data['updated_at'] = now();

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record instanceof PageSeo) {
            Cache::forget(CacheKeys::pageSeo($this->record->page->value));
        }
    }
}
