<?php

namespace App\Filament\Resources\System\Users\Users\Pages;

use App\Actions\Identity\SaveAdminUserAccess;
use App\Filament\Resources\System\Users\Users\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * @var list<string>
     */
    private array $roles = [];

    /**
     * @var list<int>
     */
    private array $centreIds = [];

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->roles = array_values($data['roles'] ?? []);
        $this->centreIds = array_map('intval', $data['centre_ids'] ?? []);

        unset($data['roles'], $data['centre_ids'], $data['password_confirmation']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();

        if (! $record instanceof User) {
            return;
        }

        app(SaveAdminUserAccess::class)(
            $record,
            $this->roles,
            $this->centreIds,
            (bool) $record->is_active,
        );
    }
}
