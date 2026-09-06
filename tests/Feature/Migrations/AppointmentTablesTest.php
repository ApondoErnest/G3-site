<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('appointment tables exist with expected columns per docs/11-erd.md', function () {
    expect(Schema::hasTable('appointment_requests'))->toBeTrue();
    expect(Schema::hasTable('appointment_status_histories'))->toBeTrue();

    expect(Schema::hasColumns('appointment_requests', [
        'id',
        'public_reference',
        'centre_id',
        'service_id',
        'vehicle_category_id',
        'registration_normalized',
        'registration_display',
        'preferred_date',
        'preferred_period',
        'contact_name',
        'contact_phone_e164',
        'contact_email',
        'preferred_channel',
        'locale',
        'status',
        'idempotency_key',
        'finalized_at',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasColumns('appointment_status_histories', [
        'id',
        'appointment_request_id',
        'status',
        'public_note',
        'actor_type',
        'actor_id',
        'created_at',
    ]))->toBeTrue();

    expect(Schema::hasColumn('appointment_status_histories', 'updated_at'))->toBeFalse();
});

test('appointment requests table enforces unique public reference per uq_appointment_requests_public_reference', function () {
    insertAppointmentRequest(['public_reference' => 'G3-26-A8FD2']);

    expect(fn () => insertAppointmentRequest(['public_reference' => 'G3-26-A8FD2']))
        ->toThrow(QueryException::class);
});

test('appointment requests table enforces unique idempotency key per uq_appointment_requests_idempotency_key', function () {
    insertAppointmentRequest(['idempotency_key' => 'submit-key-1']);

    expect(fn () => insertAppointmentRequest(['idempotency_key' => 'submit-key-1']))
        ->toThrow(QueryException::class);
});

test('appointment requests locale check allows fr and en only per chk_ar_locale', function () {
    $centreId = insertCentre(['code' => 'locale-centre-'.uniqid()]);
    $serviceId = insertService(['is_published' => true]);
    $categoryId = insertVehicleCategory(['is_published' => true]);

    insertAppointmentRequest([
        'centre_id' => $centreId,
        'service_id' => $serviceId,
        'vehicle_category_id' => $categoryId,
        'locale' => 'fr',
    ]);

    insertAppointmentRequest([
        'centre_id' => $centreId,
        'service_id' => $serviceId,
        'vehicle_category_id' => $categoryId,
        'public_reference' => 'G3-26-B1FD3',
        'locale' => 'en',
    ]);

    expect(fn () => insertAppointmentRequest([
        'centre_id' => $centreId,
        'service_id' => $serviceId,
        'vehicle_category_id' => $categoryId,
        'public_reference' => 'G3-26-C2FD4',
        'locale' => 'de',
    ]))->toThrow(QueryException::class);
});

test('appointment requests restrict centre deletion when requests exist per fk_appointment_requests_centres', function () {
    $centreId = insertCentre();
    insertAppointmentRequest(['centre_id' => $centreId]);

    expect(fn () => DB::table('centres')->where('id', $centreId)->delete())
        ->toThrow(QueryException::class);
});

test('appointment status histories cascade when request is removed per fk_appointment_status_histories_appointment_requests', function () {
    $requestId = insertAppointmentRequest();
    $historyId = insertAppointmentStatusHistory($requestId);

    DB::table('appointment_requests')->where('id', $requestId)->delete();

    expect(DB::table('appointment_status_histories')->where('id', $historyId)->exists())->toBeFalse();
});

test('appointment status histories null actor when user is removed per fk_appointment_status_histories_users', function () {
    $userId = DB::table('users')->insertGetId([
        'name' => 'Agent',
        'email' => 'agent@example.com',
        'password' => bcrypt('secret'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $requestId = insertAppointmentRequest();
    $historyId = insertAppointmentStatusHistory($requestId, [
        'actor_type' => 'user',
        'actor_id' => $userId,
    ]);

    DB::table('users')->where('id', $userId)->delete();

    expect(DB::table('appointment_status_histories')->where('id', $historyId)->value('actor_id'))->toBeNull();
});
