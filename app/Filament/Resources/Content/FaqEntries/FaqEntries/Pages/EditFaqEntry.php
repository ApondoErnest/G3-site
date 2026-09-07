<?php

namespace App\Filament\Resources\Content\FaqEntries\FaqEntries\Pages;

use App\Filament\Resources\Content\FaqEntries\FaqEntries\FaqEntryResource;
use App\Support\CacheKeys;
use App\Support\Filament\AdminForm;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditFaqEntry extends EditRecord
{
    protected static string $resource = FaqEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->after(fn (): bool => self::forgetCache($this->record->category_code) ?? true),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['question'] = AdminForm::bilingualState($data['question'] ?? null);
        $data['answer'] = AdminForm::bilingualState($data['answer'] ?? null);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['question'] = AdminForm::normalizeBilingual($data['question'] ?? null);
        $data['answer'] = AdminForm::normalizeBilingual($data['answer'] ?? null);

        return $data;
    }

    protected function afterSave(): void
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
