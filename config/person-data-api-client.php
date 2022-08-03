<?php

return [
    'environments' => [
            'local' => [
            'base_url' => env('PERSON_DATA_API_URL', ''),
            'timeout' => 25,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('PERSON_DATA_API_TOKEN', ''),
            ],
        ],
    ],
];