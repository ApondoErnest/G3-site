<?php

return [

    'supported' => ['fr', 'en'],

    'default' => 'fr',

    /*
    |--------------------------------------------------------------------------
    | Public page slugs (docs/03-scope.md)
    |--------------------------------------------------------------------------
    |
    | Keys are internal page identifiers. Values are locale-specific URL paths
    | relative to /{locale}/.
    |
    */
    'pages' => [
        'home' => ['fr' => 'accueil', 'en' => 'home'],
        'about' => ['fr' => 'a-propos', 'en' => 'about'],
        'centres' => ['fr' => 'centres', 'en' => 'centres'],
        'centre_ecole_de_police' => ['fr' => 'centres/ecole-de-police', 'en' => 'centres/ecole-de-police'],
        'centre_nomayos' => ['fr' => 'centres/nomayos', 'en' => 'centres/nomayos'],
        'services' => ['fr' => 'services', 'en' => 'services'],
        'technical_inspection' => ['fr' => 'visite-technique', 'en' => 'technical-inspection'],
        'fees' => ['fr' => 'tarifs', 'en' => 'fees'],
        'appointment' => ['fr' => 'rendez-vous', 'en' => 'appointment'],
        'road_safety' => ['fr' => 'securite-routiere', 'en' => 'road-safety'],
        'contact' => ['fr' => 'contact', 'en' => 'contact'],
    ],

];
