<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Operations notification email
    |--------------------------------------------------------------------------
    |
    | V1 delivers appointment and contact alerts to this inbox (FR-NT-01).
    | Defaults to the company settings email when unset.
    |
    */

    'ops_email' => env('G3_OPS_NOTIFICATION_EMAIL'),

    'enabled' => env('G3_NOTIFICATIONS_ENABLED', true),

];
