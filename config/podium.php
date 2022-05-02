<?php

/**
 * Configuration file for Podium.
 */
return [


    /*
    |--------------------------------------------------------------------------
    | Authentication session settings
    |--------------------------------------------------------------------------
    |
    */
    'sessions' => [
        'default' => [
            'grant_type'    => 'app',
            'app_id'        => '',
            'app_token'     => '',
            'client_id'     => '',
            'client_secret' => '',
        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | Cache settings
    |--------------------------------------------------------------------------
    |
    */
    'cache' => [
        'store' => 'default',  // Cache store to use.
        'prefix'=> 'Podium:',  // Cache prefix.
    ],
];