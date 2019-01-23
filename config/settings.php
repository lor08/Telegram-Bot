<?php

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

return [
    'db' => [
        'dsn'      => getenv('DB_DSN'),
        'username' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD')
    ],
    'token'        => getenv('TOKEN')
];