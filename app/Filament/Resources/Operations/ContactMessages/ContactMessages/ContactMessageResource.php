<?php

namespace App\Filament\Resources\Operations\ContactMessages\ContactMessages;

use App\Filament\Resources\Operations\ContactMessages\ContactMessages\Pages\ListContactMessages;
use App\Filament\Resources\Operations\ContactMessages\ContactMessages\Pages\ViewContactMessage;
use App\Filament\Resources\Operations\ContactMessages\ContactMessages\Schemas\ContactMessageInfolist;
use App\Filament\Resources\Operations\ContactMessages\ContactMessages\Tables\ContactMessagesTable;
use App\Models\Contact\ContactMessage;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static ?int $navigationSort = 20;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.operations');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.contacts.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('admin.contacts.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.contacts.navigation');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        if (! $record instanceof ContactMessage) {
            return null;
        }

        return $record->subject;
    }

    public static function infolist(Schema $schema): Schema
    {
        return ContactMessageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContactMessagesTable::configure($table);
    }

    /**
     * @return Builder<ContactMessage>
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['centre']);
        $user = auth()->user();

        if (! $user instanceof User) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasRole(['super_admin', 'operations_admin'])) {
            return $query;
        }

        if ($user->hasRole(['centre_manager', 'reception_officer'])) {
            $centreIds = $user->centreScopes()->pluck('centre_id')->all();

            if ($centreIds === []) {
                return $query->whereRaw('1 = 0');
            }

            return $query->where(function (Builder $scoped) use ($centreIds): void {
                $scoped->whereIn('centre_id', $centreIds)
                    ->orWhereNull('centre_id');
            });
        }

        return $query->whereRaw('1 = 0');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactMessages::route('/'),
            'view' => ViewContactMessage::route('/{record}'),
        ];
    }
}
