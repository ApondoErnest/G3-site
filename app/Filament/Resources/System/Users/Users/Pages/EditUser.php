<?php

namespace App\Filament\Resources\System\Users\Users\Pages;

use App\Actions\Identity\SaveAdminUserAccess;
use App\Filament\Resources\System\Users\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditUser extends EditRecord
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

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();

        if ($record instanceof User) {
            $data['roles'] = $record->roles->pluck('name')->all();
            $data['centre_ids'] = $record->centreScopes()->pluck('centre_id')->all();
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->roles = array_values($data['roles'] ?? []);
        $this->centreIds = array_map('intval', $data['centre_ids'] ?? []);

        $record = $this->getRecord();

        if ($record instanceof User) {
            try {
                app(SaveAdminUserAccess::class)->assertRetainsSuperAdmin(
                    $record,
                    $this->roles,
                    (bool) ($data['is_active'] ?? false),
                );
            } catch (ValidationException $exception) {
                throw ValidationException::withMessages([
                    'data.roles' => $exception->errors()['roles'][0] ?? __('admin.users.errors.last_super_admin'),
                ]);
            }
        }

        unset($data['roles'], $data['centre_ids'], $data['password_confirmation']);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        return $data;
    }

    protected function afterSave(): void
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
