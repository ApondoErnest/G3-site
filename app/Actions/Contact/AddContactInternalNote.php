<?php

namespace App\Actions\Contact;

use App\Actions\Contact\Data\AddContactInternalNoteData;
use App\Models\Contact\ContactInternalNote;
use App\Models\Contact\ContactMessage;

final class AddContactInternalNote
{
    use AuthorizesContactChanges;

    public function __invoke(AddContactInternalNoteData $data): ContactInternalNote
    {
        $message = ContactMessage::query()->findOrFail($data->contactMessageId);

        $this->authorizeContactChange($data->author, $message);

        return $message->internalNotes()->create([
            'author_id' => $data->author->id,
            'body' => $data->body,
        ]);
    }
}
