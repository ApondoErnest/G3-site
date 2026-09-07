<?php

namespace App\Filament\Resources\Content\ContentBlocks\ContentBlocks\Pages;

use App\Actions\Content\Data\PublishContentBlockData;
use App\Actions\Content\Data\UnpublishContentBlockData;
use App\Actions\Content\Data\UpdateContentBlockData;
use App\Actions\Content\PublishContentBlock;
use App\Actions\Content\UnpublishContentBlock;
use App\Actions\Content\UpdateContentBlock;
use App\Filament\Resources\Content\ContentBlocks\ContentBlocks\ContentBlockResource;
use App\Models\Content\ContentBlock;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class EditContentBlock extends EditRecord
{
    protected static string $resource = ContentBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('publish')
                ->label(__('admin.content.blocks.actions.publish'))
                ->visible(fn (): bool => ! $this->getRecord()->is_published)
                ->requiresConfirmation()
                ->action(function (): void {
                    try {
                        app(PublishContentBlock::class)(new PublishContentBlockData(
                            contentBlockId: $this->getRecord()->id,
                            actor: $this->actor(),
                        ));
                    } catch (InvalidArgumentException $exception) {
                        Notification::make()
                            ->danger()
                            ->title($exception->getMessage())
                            ->send();

                        return;
                    }

                    $this->refreshFormData(['is_published', 'published_at']);

                    Notification::make()
                        ->success()
                        ->title(__('admin.content.blocks.notifications.published'))
                        ->send();
                }),
            Action::make('unpublish')
                ->label(__('admin.content.blocks.actions.unpublish'))
                ->color('gray')
                ->visible(fn (): bool => (bool) $this->getRecord()->is_published)
                ->requiresConfirmation()
                ->action(function (): void {
                    app(UnpublishContentBlock::class)(new UnpublishContentBlockData(
                        contentBlockId: $this->getRecord()->id,
                        actor: $this->actor(),
                    ));

                    $this->refreshFormData(['is_published', 'published_at']);

                    Notification::make()
                        ->success()
                        ->title(__('admin.content.blocks.notifications.unpublished'))
                        ->send();
                }),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['content'] = self::contentState($data['content'] ?? null);

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof ContentBlock) {
            return $record;
        }

        $content = self::normalizeContent($data['content'] ?? null);

        return app(UpdateContentBlock::class)(new UpdateContentBlockData(
            contentBlockId: $record->id,
            content: $content,
            localeStatus: self::localeStatus($content),
            actor: $this->actor(),
        ));
    }

    private function actor(): User
    {
        $user = auth()->user();

        abort_unless($user instanceof User, 403);

        return $user;
    }

    /**
     * @param  array<string, mixed>|string|null  $content
     * @return array<string, array<string, string>>
     */
    private static function contentState(array|string|null $content): array
    {
        if (is_string($content)) {
            $content = json_decode($content, true) ?? [];
        }

        if (! is_array($content)) {
            $content = [];
        }

        return [
            'fr' => [
                'headline' => (string) ($content['fr']['headline'] ?? ''),
                'body' => (string) ($content['fr']['body'] ?? ''),
            ],
            'en' => [
                'headline' => (string) ($content['en']['headline'] ?? ''),
                'body' => (string) ($content['en']['body'] ?? ''),
            ],
        ];
    }

    /**
     * @param  array<string, mixed>|null  $content
     * @return array{fr: array{headline: string, body: string}, en: array{headline: string, body: string}}
     */
    private static function normalizeContent(?array $content): array
    {
        return [
            'fr' => [
                'headline' => (string) ($content['fr']['headline'] ?? ''),
                'body' => (string) ($content['fr']['body'] ?? ''),
            ],
            'en' => [
                'headline' => (string) ($content['en']['headline'] ?? ''),
                'body' => (string) ($content['en']['body'] ?? ''),
            ],
        ];
    }

    /**
     * @param  array{fr: array{headline: string, body: string}, en: array{headline: string, body: string}}  $content
     * @return array{fr: string, en: string}
     */
    private static function localeStatus(array $content): array
    {
        return [
            'fr' => filled($content['fr']['headline']) ? 'complete' : 'incomplete',
            'en' => filled($content['en']['headline']) ? 'complete' : 'incomplete',
        ];
    }
}
