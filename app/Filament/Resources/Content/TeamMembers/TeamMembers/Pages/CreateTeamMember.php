<?php

namespace App\Filament\Resources\Content\TeamMembers\TeamMembers\Pages;

use App\Filament\Resources\Content\TeamMembers\TeamMembers\TeamMemberResource;
use App\Support\CacheKeys;
use App\Support\Filament\AdminForm;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateTeamMember extends CreateRecord
{
    protected static string $resource = TeamMemberResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['role_title'] = AdminForm::normalizeBilingual($data['role_title'] ?? null);
        $data['bio'] = AdminForm::normalizeBilingual($data['bio'] ?? null);

        return $data;
    }

    protected function afterCreate(): void
    {
        Cache::forget(CacheKeys::teamPublic());
    }
}
