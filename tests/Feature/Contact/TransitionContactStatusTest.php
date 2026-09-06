<?php

use App\Actions\Contact\AddContactInternalNote;
use App\Actions\Contact\Data\AddContactInternalNoteData;
use App\Actions\Contact\Data\TransitionContactStatusData;
use App\Actions\Contact\TransitionContactStatus;
use App\Domain\Contact\ContactStateMachine;
use App\Domain\Contact\InvalidContactTransitionException;
use App\Domain\Enums\ContactStatus;
use App\Models\Identity\AdminUserScope;
use App\Models\User;
use Database\Seeders\BaselineCentresSeeder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(BaselineCentresSeeder::class);
    Role::create(['name' => 'reception_officer', 'guard_name' => 'web']);
    Role::create(['name' => 'content_editor', 'guard_name' => 'web']);
});

function receptionUserForContact(?int $centreId = null): User
{
    $user = User::factory()->create();
    $user->assignRole('reception_officer');

    if ($centreId !== null) {
        AdminUserScope::query()->create([
            'user_id' => $user->id,
            'centre_id' => $centreId,
            'created_at' => now(),
        ]);
    }

    return $user;
}

test('contact status workflow new to in progress to resolved FR-CT-03', function () {
    $messageId = insertContactMessage();
    $user = receptionUserForContact();

    app(TransitionContactStatus::class)(new TransitionContactStatusData(
        contactMessageId: $messageId,
        toStatus: ContactStatus::InProgress,
        actor: $user,
    ));

    app(TransitionContactStatus::class)(new TransitionContactStatusData(
        contactMessageId: $messageId,
        toStatus: ContactStatus::Resolved,
        actor: $user,
    ));

    $message = DB::table('contact_messages')->where('id', $messageId)->first();

    expect($message->status)->toBe('resolved')
        ->and($message->resolved_at)->not->toBeNull();
});

test('resolved contact cannot transition back to in progress', function () {
    $messageId = insertContactMessage(['status' => 'resolved', 'resolved_at' => now()]);
    $user = receptionUserForContact();

    expect(fn () => app(TransitionContactStatus::class)(new TransitionContactStatusData(
        contactMessageId: $messageId,
        toStatus: ContactStatus::InProgress,
        actor: $user,
    )))->toThrow(InvalidContactTransitionException::class);
});

test('scoped reception can manage centre-specific contact messages', function () {
    $centreId = centreId('ecole-de-police');
    $messageId = insertContactMessage(['centre_id' => $centreId]);
    $user = receptionUserForContact($centreId);

    $message = app(TransitionContactStatus::class)(new TransitionContactStatusData(
        contactMessageId: $messageId,
        toStatus: ContactStatus::InProgress,
        actor: $user,
    ));

    expect($message->status)->toBe(ContactStatus::InProgress);
});

test('reception without centre scope cannot manage other centre messages FR-AD-03', function () {
    $messageId = insertContactMessage(['centre_id' => centreId('nomayos')]);
    $user = receptionUserForContact(centreId('ecole-de-police'));

    expect(fn () => app(TransitionContactStatus::class)(new TransitionContactStatusData(
        contactMessageId: $messageId,
        toStatus: ContactStatus::InProgress,
        actor: $user,
    )))->toThrow(AuthorizationException::class);
});

test('content editor cannot transition contact messages', function () {
    $messageId = insertContactMessage();
    $user = User::factory()->create();
    $user->assignRole('content_editor');

    expect(fn () => app(TransitionContactStatus::class)(new TransitionContactStatusData(
        contactMessageId: $messageId,
        toStatus: ContactStatus::InProgress,
        actor: $user,
    )))->toThrow(AuthorizationException::class);
});

test('internal note is stored on contact thread', function () {
    $messageId = insertContactMessage();
    $user = receptionUserForContact();

    app(AddContactInternalNote::class)(new AddContactInternalNoteData(
        contactMessageId: $messageId,
        body: 'Client prefers afternoon callback.',
        author: $user,
    ));

    expect(DB::table('contact_internal_notes')->count())->toBe(1);
});

test('final resolved state has no allowed transitions', function () {
    $machine = app(ContactStateMachine::class);

    expect($machine->allowedTransitions(ContactStatus::Resolved))->toBe([]);
});
