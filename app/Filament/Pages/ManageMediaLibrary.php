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
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ManageMediaLibrary extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?int $navigationSort = 50;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.groups.content');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.media.navigation');
    }

    public function getTitle(): string|Htmlable
    {
        return __('admin.media.title');
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User
            && $user->hasRole(['super_admin', 'content_editor']);
    }

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('admin.media.fields.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('file_name')
                    ->label(__('admin.media.fields.file_name'))
                    ->searchable(),
                TextColumn::make('collection_name')
                    ->label(__('admin.media.fields.collection'))
                    ->sortable(),
                TextColumn::make('mime_type')
                    ->label(__('admin.media.fields.mime_type')),
                TextColumn::make('size')
                    ->label(__('admin.media.fields.size'))
                    ->formatStateUsing(fn (int $state): string => number_format($state / 1024, 1).' KB'),
                TextColumn::make('model_type')
                    ->label(__('admin.media.fields.model_type'))
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : ''),
                TextColumn::make('created_at')
                    ->label(__('admin.media.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([25, 50, 100]);
    }

    protected function getTableQuery(): Builder
    {
        return Media::query();
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                EmbeddedTable::make(),
            ]);
    }
}
