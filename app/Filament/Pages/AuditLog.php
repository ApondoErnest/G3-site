<?php

namespace App\Filament\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class AuditLog extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?int $navigationSort = 90;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.system');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.audit.navigation');
    }

    public function getTitle(): string|Htmlable
    {
        return __('admin.audit.title');
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User
            && $user->hasRole(['super_admin', 'operations_admin']);
    }

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                    ->label(__('admin.audit.fields.description'))
                    ->searchable()
                    ->wrap(),
                TextColumn::make('log_name')
                    ->label(__('admin.audit.fields.log_name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject_type')
                    ->label(__('admin.audit.fields.subject_type'))
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '—')
                    ->sortable(),
                TextColumn::make('causer.name')
                    ->label(__('admin.audit.fields.causer'))
                    ->default('—'),
                TextColumn::make('created_at')
                    ->label(__('admin.audit.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([25, 50, 100]);
    }

    protected function getTableQuery(): Builder
    {
        return Activity::query();
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                EmbeddedTable::make(),
            ]);
    }
}
