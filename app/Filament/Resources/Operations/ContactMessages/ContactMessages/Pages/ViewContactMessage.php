<?php

namespace App\Filament\Resources\Operations\ContactMessages\ContactMessages\Pages;

use App\Actions\Contact\AddContactInternalNote;
use App\Actions\Contact\Data\AddContactInternalNoteData;
use App\Actions\Contact\Data\TransitionContactStatusData;
use App\Actions\Contact\TransitionContactStatus;
use App\Domain\Contact\ContactStateMachine;
use App\Domain\Contact\InvalidContactTransitionException;
use App\Domain\Enums\ContactStatus;
use App\Filament\Resources\Operations\ContactMessages\ContactMessages\ContactMessageResource;
use App\Models\Contact\ContactMessage;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

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
        /** @var ContactMessage $record */
        $record = parent::resolveRecord($key);

        return $record->load([
            'centre',
            'internalNotes.author',
        ]);
    }

    protected function getHeaderActions(): array
    {
        /** @var ContactMessage $record */
        $record = $this->getRecord();
        $actions = [];

        foreach (app(ContactStateMachine::class)->allowedTransitions($record->status) as $toStatus) {
            $actions[] = Action::make('transition_'.$toStatus->value)
                ->label(__('admin.contacts.transitions.'.$toStatus->value))
                ->color($toStatus === ContactStatus::Resolved ? 'success' : 'primary')
                ->action(fn () => $this->transitionStatus($toStatus));
        }

        return $actions;
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getInfolistContentComponent(),
                Form::make([
                    Section::make(__('admin.contacts.sections.add_note'))
                        ->schema([
                            Textarea::make('body')
                                ->label(__('admin.contacts.fields.note_body'))
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
                                ->label(__('admin.contacts.actions.save_note'))
                                ->submit('saveInternalNote'),
                        ]),
                    ]),
            ]);
    }

    public function transitionStatus(ContactStatus $toStatus): void
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        try {
            app(TransitionContactStatus::class)(new TransitionContactStatusData(
                contactMessageId: $this->getRecord()->getKey(),
                toStatus: $toStatus,
                actor: $user,
            ));

            $this->refreshRecord();

            Notification::make()
                ->success()
                ->title(__('admin.contacts.notifications.transitioned'))
                ->send();
        } catch (InvalidContactTransitionException) {
            Notification::make()
                ->danger()
                ->title(__('admin.contacts.notifications.invalid_transition'))
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

        app(AddContactInternalNote::class)(new AddContactInternalNoteData(
            contactMessageId: $this->getRecord()->getKey(),
            body: $body,
            author: $user,
        ));

        $this->noteData = ['body' => ''];
        $this->refreshRecord();

        Notification::make()
            ->success()
            ->title(__('admin.contacts.notifications.note_saved'))
            ->send();
    }

    private function refreshRecord(): void
    {
        /** @var ContactMessage $record */
        $record = $this->getRecord()->fresh([
            'centre',
            'internalNotes.author',
        ]);

        $this->record = $record;
    }
}
