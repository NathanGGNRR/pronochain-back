<?php

use App\Models\RefreshToken;

return [
    // Time to live for access tokens (in seconds)
    'time_to_live_access' => 36000,
    // Time to live for remember tokens (in seconds)
    'time_to_live_refresh' => 604800,

    // Time to live for reset password token (in seconds)
    'time_to_live_token_reset_password' => 1800,

    'secret' => env('JWT_SECRET'),

    /*
     * Location of remember_token
     *
     * Table's name of refresh tokens (polymorphic relations)
     *
     * Remember token are used to get new access_token
     * Remember tokens are generated with UUID (version 4)
     */
    'refresh_token_model' => RefreshToken::class,
    'credentials' => [
        'address' => [
            'equivalent' => 'eth_address',
        ],
        'signature' => [
            'equivalent' => null,
        ],
        'message' => [
            'equivalent' => null,
        ],
    ],
    'with_claims' => [
        'user' => [
            'user_id' => 'id',
        ],
    ],
];
