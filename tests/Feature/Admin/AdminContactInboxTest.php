<?php

use App\Domain\Enums\ContactStatus;
use App\Filament\Resources\Operations\ContactMessages\ContactMessages\ContactMessageResource;
use App\Filament\Resources\Operations\ContactMessages\ContactMessages\Pages\ListContactMessages;
use App\Filament\Resources\Operations\ContactMessages\ContactMessages\Pages\ViewContactMessage;
use App\Models\Contact\ContactMessage;
use App\Models\Identity\AdminUserScope;
use Database\Seeders\BaselineCentresSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['admin.mfa_required' => false]);
    seedAdminRoles();
    $this->seed(BaselineCentresSeeder::class);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

test('operations roles can open the contact inbox', function (): void {
    insertContactMessage([
        'name' => 'Marie N.',
        'subject' => 'Question horaires',
    ]);

    $user = createAdminUser('operations_admin');

    $this->actingAs($user)
        ->get(ContactMessageResource::getUrl('index'))
        ->assertOk()
        ->assertSee(__('admin.contacts.navigation', locale: 'fr'))
        ->assertSee('Marie N.')
        ->assertSee('Question horaires');
});

test('centre manager inbox is scoped to the assigned centre and unassigned messages', function (): void {
    $ecoleId = centreId('ecole-de-police');
    $ownId = insertContactMessage([
        'centre_id' => $ecoleId,
        'name' => 'Client École',
    ]);
    $unassignedId = insertContactMessage([
        'centre_id' => null,
        'name' => 'Client sans centre',
    ]);
    $otherId = insertContactMessage([
        'centre_id' => centreId('nomayos'),
        'name' => 'Client Nomayos',
    ]);

    $user = createAdminUser('centre_manager');
    AdminUserScope::query()->create([
        'user_id' => $user->id,
        'centre_id' => $ecoleId,
        'created_at' => now(),
    ]);

    Livewire::actingAs($user)
        ->test(ListContactMessages::class)
        ->assertCanSeeTableRecords([
            ContactMessage::query()->findOrFail($ownId),
            ContactMessage::query()->findOrFail($unassignedId),
        ])
        ->assertCanNotSeeTableRecords([
            ContactMessage::query()->findOrFail($otherId),
        ]);

    $this->actingAs($user)
        ->get(ContactMessageResource::getUrl('view', ['record' => $otherId]))
        ->assertNotFound();
});

test('contact status follows the allowed workflow and stores an internal note', function (): void {
    $centreId = centreId('ecole-de-police');
    $messageId = insertContactMessage([
        'centre_id' => $centreId,
        'status' => 'new',
    ]);
    $resolvedId = insertContactMessage([
        'centre_id' => $centreId,
        'status' => 'resolved',
        'resolved_at' => now(),
    ]);

    $user = createAdminUser('reception_officer');
    AdminUserScope::query()->create([
        'user_id' => $user->id,
        'centre_id' => $centreId,
        'created_at' => now(),
    ]);

    Livewire::actingAs($user)
        ->test(ViewContactMessage::class, ['record' => $resolvedId])
        ->assertActionDoesNotExist('transition_in_progress')
        ->assertActionDoesNotExist('transition_resolved');

    Livewire::actingAs($user)
        ->test(ViewContactMessage::class, ['record' => $resolvedId])
        ->call('transitionStatus', ContactStatus::InProgress)
        ->assertNotified(__('admin.contacts.notifications.invalid_transition'));

    expect(ContactMessage::query()->findOrFail($resolvedId)->status)->toBe(ContactStatus::Resolved);

    Livewire::actingAs($user)
        ->test(ViewContactMessage::class, ['record' => $messageId])
        ->call('transitionStatus', ContactStatus::InProgress)
        ->assertNotified(__('admin.contacts.notifications.transitioned'))
        ->set('noteData.body', 'Rappeler la cliente demain matin.')
        ->call('saveInternalNote')
        ->assertNotified(__('admin.contacts.notifications.note_saved'))
        ->assertSee('Rappeler la cliente demain matin.');

    $message = ContactMessage::query()->findOrFail($messageId);

    expect($message->status)->toBe(ContactStatus::InProgress)
        ->and($message->internalNotes()->count())->toBe(1);
});
