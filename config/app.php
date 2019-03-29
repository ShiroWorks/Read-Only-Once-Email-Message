<?php

return [
    'url' => 'http://localhost:8888',
    'db' => [
        'mysql' => [
            'host' => '127.0.0.1',
            'dbname' => 'your dbname',
            'username' => 'root',
            'password' => '',
        ],
    ],
    'services' => [
        'mailgun' => [
            'domain' => 'your mailgun domain',
            'secret' => 'your mailgun secret api',
        ],
    ]
];