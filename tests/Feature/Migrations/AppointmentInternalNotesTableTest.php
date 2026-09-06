<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('appointment internal notes table exists with expected columns per docs/11-erd.md', function () {
    expect(Schema::hasTable('appointment_internal_notes'))->toBeTrue();

    expect(Schema::hasColumns('appointment_internal_notes', [
        'id',
        'appointment_request_id',
        'author_id',
        'body',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('appointment internal notes cascade when request is removed per fk_appointment_internal_notes_appointment_requests', function () {
    $requestId = insertInternalNotesAppointmentRequest();
    $noteId = insertInternalNote($requestId);

    DB::table('appointment_requests')->where('id', $requestId)->delete();

    expect(DB::table('appointment_internal_notes')->where('id', $noteId)->exists())->toBeFalse();
});

test('appointment internal notes restrict author deletion per fk_appointment_internal_notes_users', function () {
    $authorId = insertInternalNotesAuthor();
    $requestId = insertInternalNotesAppointmentRequest();
    insertInternalNote($requestId, ['author_id' => $authorId]);

    expect(fn () => DB::table('users')->where('id', $authorId)->delete())
        ->toThrow(QueryException::class);
});

function insertInternalNotesAuthor(): int
{
    return DB::table('users')->insertGetId([
        'name' => 'Staff Author',
        'email' => 'staff-'.uniqid().'@example.com',
        'password' => bcrypt('secret'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

function insertInternalNotesAppointmentRequest(): int
{
    $centreId = insertCentre();
    $serviceId = DB::table('services')->insertGetId([
        'code' => 'service-'.uniqid(),
        'title' => json_encode(['fr' => 'Visite technique', 'en' => 'Technical inspection']),
        'summary' => null,
        'body' => null,
        'icon' => null,
        'sort_order' => 1,
        'is_published' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $categoryId = DB::table('vehicle_categories')->insertGetId([
        'code' => 'category-'.uniqid(),
        'label' => json_encode(['fr' => 'Véhicule léger', 'en' => 'Light vehicle']),
        'examples' => null,
        'description' => null,
        'sort_order' => 1,
        'is_published' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return DB::table('appointment_requests')->insertGetId([
        'public_reference' => 'G3-26-'.strtoupper(substr(uniqid(), -5)),
        'centre_id' => $centreId,
        'service_id' => $serviceId,
        'vehicle_category_id' => $categoryId,
        'registration_normalized' => 'LT123AB',
        'registration_display' => 'LT 123 AB',
        'preferred_date' => '2026-09-10',
        'preferred_period' => 'morning',
        'contact_name' => 'Jean Dupont',
        'contact_phone_e164' => '+237687187516',
        'contact_email' => null,
        'preferred_channel' => 'phone',
        'locale' => 'fr',
        'status' => 'received',
        'idempotency_key' => null,
        'finalized_at' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

/**
 * @param  array<string, mixed>  $overrides
 */
function insertInternalNote(int $requestId, array $overrides = []): int
{
    return DB::table('appointment_internal_notes')->insertGetId(array_merge([
        'appointment_request_id' => $requestId,
        'author_id' => insertInternalNotesAuthor(),
        'body' => 'Client rappelé — véhicule prêt pour le contrôle.',
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides));
}
