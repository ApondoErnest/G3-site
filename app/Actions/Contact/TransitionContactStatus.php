<?php

namespace App\Actions\Contact;

use App\Actions\Contact\Data\TransitionContactStatusData;
use App\Domain\Contact\ContactStateMachine;
use App\Domain\Enums\ContactStatus;
use App\Models\Contact\ContactMessage;
use Illuminate\Support\Facades\DB;

final class TransitionContactStatus
{
    use AuthorizesContactChanges;

    public function __construct(
        private ContactStateMachine $stateMachine,
    ) {}

    public function __invoke(TransitionContactStatusData $data): ContactMessage
    {
        $message = ContactMessage::query()->findOrFail($data->contactMessageId);

        $this->authorizeContactChange($data->actor, $message);

        $from = $message->status;
        $this->stateMachine->assertCanTransition($from, $data->toStatus);

        DB::transaction(function () use ($message, $data): void {
            $updates = ['status' => $data->toStatus];

            if ($data->toStatus === ContactStatus::Resolved && $message->resolved_at === null) {
                $updates['resolved_at'] = now();
            }

            $message->update($updates);
        });

        return $message->refresh();
    }
}
