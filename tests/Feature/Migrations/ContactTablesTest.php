<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('contact tables exist with expected columns per docs/11-erd.md', function () {
    expect(Schema::hasTable('contact_messages'))->toBeTrue();
    expect(Schema::hasTable('contact_internal_notes'))->toBeTrue();

    expect(Schema::hasColumns('contact_messages', [
        'id',
        'intent',
        'status',
        'name',
        'phone_e164',
        'email',
        'subject',
        'centre_id',
        'message',
        'locale',
        'resolved_at',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasColumns('contact_internal_notes', [
        'id',
        'contact_message_id',
        'author_id',
        'body',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('contact messages locale check allows fr and en only per chk_cm_locale', function () {
    insertContactMessage(['locale' => 'fr']);
    insertContactMessage(['locale' => 'en']);

    expect(fn () => insertContactMessage(['locale' => 'de']))
        ->toThrow(QueryException::class);
});

test('contact messages restrict centre deletion when messages exist per fk_contact_messages_centres', function () {
    $centreId = insertCentre();
    insertContactMessage(['centre_id' => $centreId]);

    expect(fn () => DB::table('centres')->where('id', $centreId)->delete())
        ->toThrow(QueryException::class);
});

test('contact internal notes cascade when message is removed per fk_contact_internal_notes_contact_messages', function () {
    $messageId = insertContactMessage();
    $noteId = insertContactInternalNote($messageId);

    DB::table('contact_messages')->where('id', $messageId)->delete();

    expect(DB::table('contact_internal_notes')->where('id', $noteId)->exists())->toBeFalse();
});

test('contact internal notes restrict author deletion per fk_contact_internal_notes_users', function () {
    $authorId = insertContactAuthor();
    $messageId = insertContactMessage();
    insertContactInternalNote($messageId, ['author_id' => $authorId]);

    expect(fn () => DB::table('users')->where('id', $authorId)->delete())
        ->toThrow(QueryException::class);
});
