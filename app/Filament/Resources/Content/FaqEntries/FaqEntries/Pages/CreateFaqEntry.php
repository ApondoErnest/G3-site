<?php

namespace App\Filament\Resources\Content\FaqEntries\FaqEntries\Pages;

use App\Filament\Resources\Content\FaqEntries\FaqEntries\FaqEntryResource;
use App\Support\CacheKeys;
use App\Support\Filament\AdminForm;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateFaqEntry extends CreateRecord
{
    protected static string $resource = FaqEntryResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['question'] = AdminForm::normalizeBilingual($data['question'] ?? null);
        $data['answer'] = AdminForm::normalizeBilingual($data['answer'] ?? null);

        return $data;
    }

    protected function afterCreate(): void
    {
        self::forgetCache($this->record->category_code);
    }

    private static function forgetCache(?string $categoryCode): void
    {
        Cache::forget(CacheKeys::faqPublished());

        if ($categoryCode !== null) {
            Cache::forget(CacheKeys::faqPublished($categoryCode));
        }
    }
}
