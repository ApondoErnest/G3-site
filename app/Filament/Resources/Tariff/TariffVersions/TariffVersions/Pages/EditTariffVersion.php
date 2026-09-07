<?php

namespace App\Filament\Resources\Tariff\TariffVersions\TariffVersions\Pages;

use App\Actions\Tariff\Data\MarkTariffVersionReviewedData;
use App\Actions\Tariff\Data\PublishTariffVersionData;
use App\Actions\Tariff\Data\TariffItemData;
use App\Actions\Tariff\Data\UpdateTariffVersionItemsData;
use App\Actions\Tariff\MarkTariffVersionReviewed;
use App\Actions\Tariff\PublishTariffVersion;
use App\Actions\Tariff\UpdateTariffVersionItems;
use App\Domain\Enums\TariffVersionStatus;
use App\Filament\Resources\Tariff\TariffVersions\TariffVersions\TariffVersionResource;
use App\Models\Tariff\TariffItem;
use App\Models\Tariff\TariffVersion;
use App\Support\Filament\AdminForm;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use InvalidArgumentException;
use Throwable;

class EditTariffVersion extends EditRecord
{
    use CanUseDatabaseTransactions;

    protected static string $resource = TariffVersionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('markReviewed')
                ->label(__('admin.tariffs.actions.mark_reviewed'))
                ->visible(fn (): bool => $this->getRecord()->status === TariffVersionStatus::Draft)
                ->requiresConfirmation()
                ->action(function (): void {
                    try {
                        app(MarkTariffVersionReviewed::class)(new MarkTariffVersionReviewedData(
                            tariffVersionId: $this->getRecord()->id,
                            actor: auth()->user(),
                        ));

                        Notification::make()
                            ->success()
                            ->title(__('admin.tariffs.notifications.marked_reviewed'))
                            ->send();

                        $this->record->refresh();
                        $this->fillForm();
                    } catch (InvalidArgumentException|AuthorizationException $exception) {
                        Notification::make()
                            ->danger()
                            ->title(__('admin.tariffs.notifications.error'))
                            ->body($exception->getMessage())
                            ->send();
                    }
                }),
            Action::make('publish')
                ->label(__('admin.tariffs.actions.publish'))
                ->visible(fn (): bool => $this->getRecord()->status === TariffVersionStatus::Reviewed)
                ->authorize(fn (): bool => auth()->user()->can('publish', $this->getRecord()))
                ->modalHeading(__('admin.tariffs.actions.publish'))
                ->modalDescription(__('admin.tariffs.actions.publish_confirm'))
                ->schema([
                    DatePicker::make('confirm_effective_from')
                        ->label(__('admin.tariffs.fields.effective_from'))
                        ->default(fn (): string => $this->getRecord()->effective_from->toDateString())
                        ->required()
                        ->native(false),
                ])
                ->action(function (array $data): void {
                    try {
                        app(PublishTariffVersion::class)(new PublishTariffVersionData(
                            tariffVersionId: $this->getRecord()->id,
                            confirmEffectiveFrom: CarbonImmutable::parse($data['confirm_effective_from']),
                            actor: auth()->user(),
                        ));

                        Notification::make()
                            ->success()
                            ->title(__('admin.tariffs.notifications.published'))
                            ->send();

                        $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->getRecord()]));
                    } catch (InvalidArgumentException|AuthorizationException $exception) {
                        Notification::make()
                            ->danger()
                            ->title(__('admin.tariffs.notifications.error'))
                            ->body($exception->getMessage())
                            ->send();
                    }
                }),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var TariffVersion $record */
        $record = $this->getRecord();
        $record->loadMissing(['items.centres']);

        $data['tariff_items'] = $record->items
            ->map(fn (TariffItem $item): array => [
                'vehicle_category_id' => $item->vehicle_category_id,
                'service_id' => $item->service_id,
                'amount_xaf' => $item->amount_xaf,
                'centres' => $item->centres->pluck('id')->all(),
                'validity_notes' => AdminForm::bilingualState($item->validity_notes),
                'sort_order' => $item->sort_order,
            ])
            ->values()
            ->all();

        return $data;
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        try {
            $this->beginDatabaseTransaction();

            $data = $this->form->getState();
            /** @var TariffVersion $record */
            $record = $this->getRecord();

            if (in_array($record->status, [TariffVersionStatus::Draft, TariffVersionStatus::Reviewed], true)) {
                $record->update([
                    'label' => $data['label'],
                    'effective_from' => $data['effective_from'],
                    'effective_until' => filled($data['effective_until'] ?? null) ? $data['effective_until'] : null,
                ]);

                app(UpdateTariffVersionItems::class)(new UpdateTariffVersionItemsData(
                    tariffVersionId: $record->id,
                    items: self::mapTariffItems($data['tariff_items'] ?? []),
                    actor: auth()->user(),
                ));
            }

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction()
                ? $this->rollBackDatabaseTransaction()
                : $this->commitDatabaseTransaction();

            return;
        } catch (InvalidArgumentException|AuthorizationException $exception) {
            $this->rollBackDatabaseTransaction();

            Notification::make()
                ->danger()
                ->title(__('admin.tariffs.notifications.error'))
                ->body($exception->getMessage())
                ->send();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        if ($shouldSendSavedNotification) {
            Notification::make()
                ->success()
                ->title(__('admin.tariffs.notifications.saved'))
                ->send();
        }

        if ($shouldRedirect) {
            $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->getRecord()]));
        }
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return list<TariffItemData>
     */
    private static function mapTariffItems(array $items): array
    {
        return collect($items)
            ->values()
            ->map(function (array $row, int $index): TariffItemData {
                return new TariffItemData(
                    vehicleCategoryId: (int) $row['vehicle_category_id'],
                    amountXaf: (int) $row['amount_xaf'],
                    centreIds: array_map('intval', $row['centres'] ?? []),
                    serviceId: filled($row['service_id'] ?? null) ? (int) $row['service_id'] : null,
                    validityNotes: self::normalizeValidityNotes($row['validity_notes'] ?? null),
                    sortOrder: (int) ($row['sort_order'] ?? ($index + 1)),
                );
            })
            ->all();
    }

    /**
     * @param  array<string, mixed>|null  $notes
     * @return array{fr: string, en: string}|null
     */
    private static function normalizeValidityNotes(?array $notes): ?array
    {
        if ($notes === null) {
            return null;
        }

        $normalized = AdminForm::normalizeBilingual($notes);

        if ($normalized['fr'] === '' && $normalized['en'] === '') {
            return null;
        }

        return $normalized;
    }
}
