<?php

namespace App\Filament\Resources\Operations\AppointmentRequests\AppointmentRequests\Pages;

use App\Actions\Appointment\AddAppointmentInternalNote;
use App\Actions\Appointment\Data\AddAppointmentInternalNoteData;
use App\Actions\Appointment\Data\TransitionAppointmentStatusData;
use App\Actions\Appointment\Data\UpdateAppointmentPreferredTimeData;
use App\Actions\Appointment\TransitionAppointmentStatus;
use App\Actions\Appointment\UpdateAppointmentPreferredTime;
use App\Domain\Appointment\AppointmentStateMachine;
use App\Domain\Appointment\InvalidAppointmentTransitionException;
use App\Domain\Enums\AppointmentStatus;
use App\Domain\Enums\PreferredPeriod;
use App\Filament\Resources\Operations\AppointmentRequests\AppointmentRequests\AppointmentRequestResource;
use App\Models\Appointment\AppointmentRequest;
use App\Models\User;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class ViewAppointmentRequest extends ViewRecord
{
    protected static string $resource = AppointmentRequestResource::class;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $noteData = [];

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->noteData = ['body' => ''];
    }

    protected function resolveRecord(int|string $key): Model
    {
        /** @var AppointmentRequest $record */
        $record = parent::resolveRecord($key);

        return $record->load([
            'centre',
            'service',
            'vehicleCategory',
            'statusHistories',
            'internalNotes.author',
        ]);
    }

    protected function getHeaderActions(): array
    {
        /** @var AppointmentRequest $record */
        $record = $this->getRecord();
        $machine = app(AppointmentStateMachine::class);
        $actions = [];

        foreach ($machine->allowedTransitions($record->status) as $toStatus) {
            $actions[] = Action::make('transition_'.$toStatus->value)
                ->label(__('admin.appointments.transitions.'.$toStatus->value))
                ->color(match ($toStatus) {
                    AppointmentStatus::Cancelled => 'danger',
                    AppointmentStatus::Confirmed, AppointmentStatus::Completed => 'success',
                    default => 'primary',
                })
                ->requiresConfirmation($toStatus === AppointmentStatus::Cancelled)
                ->action(fn () => $this->transitionStatus($toStatus));
        }

        if (! $record->status->isFinal()) {
            $actions[] = Action::make('reschedule')
                ->label(__('admin.appointments.actions.reschedule'))
                ->schema([
                    DatePicker::make('preferred_date')
                        ->label(__('admin.appointments.fields.preferred_date'))
                        ->required()
                        ->default($record->preferred_date),
                    Select::make('preferred_period')
                        ->label(__('admin.appointments.fields.preferred_period'))
                        ->options(collect(PreferredPeriod::cases())
                            ->mapWithKeys(fn (PreferredPeriod $period): array => [
                                $period->value => __('admin.appointments.periods.'.$period->value),
                            ])
                            ->all())
                        ->required()
                        ->native(false)
                        ->default($record->preferred_period?->value),
                ])
                ->action(function (array $data): void {
                    $this->reschedule($data);
                });
        }

        return $actions;
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getInfolistContentComponent(),
                Form::make([
                    Section::make(__('admin.appointments.sections.add_note'))
                        ->schema([
                            Textarea::make('body')
                                ->label(__('admin.appointments.fields.note_body'))
                                ->required()
                                ->rows(4)
                                ->maxLength(5000),
                        ]),
                ])
                    ->statePath('noteData')
                    ->livewireSubmitHandler('saveInternalNote')
                    ->footer([
                        Actions::make([
                            Action::make('saveInternalNote')
                                ->label(__('admin.appointments.actions.save_note'))
                                ->submit('saveInternalNote'),
                        ]),
                    ]),
            ]);
    }

    public function transitionStatus(AppointmentStatus $toStatus): void
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        try {
            app(TransitionAppointmentStatus::class)(new TransitionAppointmentStatusData(
                appointmentId: $this->getRecord()->getKey(),
                toStatus: $toStatus,
                actor: $user,
            ));

            $this->refreshRecord();

            Notification::make()
                ->success()
                ->title(__('admin.appointments.notifications.transitioned'))
                ->send();
        } catch (InvalidAppointmentTransitionException) {
            Notification::make()
                ->danger()
                ->title(__('admin.appointments.notifications.invalid_transition'))
                ->send();
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function reschedule(array $data): void
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        try {
            app(UpdateAppointmentPreferredTime::class)(new UpdateAppointmentPreferredTimeData(
                appointmentId: $this->getRecord()->getKey(),
                preferredDate: CarbonImmutable::parse($data['preferred_date']),
                preferredPeriod: PreferredPeriod::from($data['preferred_period']),
                actor: $user,
            ));

            $this->refreshRecord();

            Notification::make()
                ->success()
                ->title(__('admin.appointments.notifications.rescheduled'))
                ->send();
        } catch (ValidationException $exception) {
            Notification::make()
                ->danger()
                ->title(collect($exception->errors())->flatten()->first() ?? __('admin.appointments.notifications.invalid_transition'))
                ->send();
        }
    }

    public function saveInternalNote(): void
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        $body = trim((string) ($this->noteData['body'] ?? ''));

        if ($body === '') {
            return;
        }

        app(AddAppointmentInternalNote::class)(new AddAppointmentInternalNoteData(
            appointmentId: $this->getRecord()->getKey(),
            body: $body,
            author: $user,
        ));

        $this->noteData = ['body' => ''];
        $this->refreshRecord();

        Notification::make()
            ->success()
            ->title(__('admin.appointments.notifications.note_saved'))
            ->send();
    }

    private function refreshRecord(): void
    {
        /** @var AppointmentRequest $record */
        $record = $this->getRecord()->fresh([
            'centre',
            'service',
            'vehicleCategory',
            'statusHistories',
            'internalNotes.author',
        ]);

        $this->record = $record;
    }
}
