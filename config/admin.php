<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Multi-factor authentication
    |--------------------------------------------------------------------------
    |
    | When enabled, every admin user must configure app authentication (TOTP)
    | before accessing the panel (FR-AD-05).
    |
    */

    'mfa_required' => env('ADMIN_MFA_REQUIRED', env('APP_ENV') === 'production'),

    /*
    |--------------------------------------------------------------------------
    | Admin UI locales (FR-AD · bilingual chrome)
    |--------------------------------------------------------------------------
    */

    'locales' => ['fr', 'en'],

    'default_locale' => env('ADMIN_DEFAULT_LOCALE', 'fr'),

];
