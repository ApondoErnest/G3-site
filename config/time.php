<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Storage timezone (BR-TIME-003)
    |--------------------------------------------------------------------------
    |
    | Database timestamps and audit fields are stored in UTC. Matches
    | config('app.timezone') — do not change without change control.
    |
    */
    'storage_timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Display timezone (BR-TIME-001)
    |--------------------------------------------------------------------------
    |
    | Wall-clock for centre hours, "open now", and public-facing times.
    |
    */
    'display_timezone' => env('G3_DISPLAY_TIMEZONE', 'Africa/Douala'),

];
