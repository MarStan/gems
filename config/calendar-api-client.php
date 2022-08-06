<?php

return [
    'environments' => [
        'local' => [
            'base_url' => env('CALENDAR_API_URL', ''),
            'timeout' => 25,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('CALENDAR_API_TOKEN', ''),
            ],
        ],
    ],
];
