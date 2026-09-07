<?php

namespace App\Filament\Resources\Content\TeamMembers\TeamMembers\Pages;

use App\Filament\Resources\Content\TeamMembers\TeamMembers\TeamMemberResource;
use App\Support\CacheKeys;
use App\Support\Filament\AdminForm;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditTeamMember extends EditRecord
{
    protected static string $resource = TeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->after(fn (): bool => Cache::forget(CacheKeys::teamPublic()) ?? true),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['role_title'] = AdminForm::bilingualState($data['role_title'] ?? null);
        $data['bio'] = AdminForm::bilingualState($data['bio'] ?? null);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['role_title'] = AdminForm::normalizeBilingual($data['role_title'] ?? null);
        $data['bio'] = AdminForm::normalizeBilingual($data['bio'] ?? null);

        return $data;
    }

    protected function afterSave(): void
    {
        Cache::forget(CacheKeys::teamPublic());
    }
}
