<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('settings table exists with group and name unique constraint per BR-COMP-001 storage', function () {
    expect(Schema::hasTable('settings'))->toBeTrue();

    expect(Schema::hasColumns('settings', [
        'id',
        'group',
        'name',
        'locked',
        'payload',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('settings table enforces unique group and name combination', function () {
    DB::table('settings')->insert([
        'group' => 'test',
        'name' => 'unique_property',
        'locked' => false,
        'payload' => json_encode('first'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    expect(fn () => DB::table('settings')->insert([
        'group' => 'test',
        'name' => 'unique_property',
        'locked' => false,
        'payload' => json_encode('duplicate'),
        'created_at' => now(),
        'updated_at' => now(),
    ]))->toThrow(QueryException::class);
});
