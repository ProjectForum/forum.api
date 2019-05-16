<?php

return [
    'app' => [
        'name' => 'Forum',
        'url' => 'http://localhost',
        'key' => '',
    ],
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => '',
        'username' => '',
        'password' => '',
        'prefix' => 'forum_',
        'socket' => '',
    ],
    'redis' => [
        'client' => 'predis',
        'host' => '',
        'password' => '',
        'port' => 6379,
        'db' => 0,
        'cacheDb' => 1,
    ],
    'cache' => [
        'driver' => 'file',
        'prefix' => 'forumCache_',
    ],
    'jwt' => [
        'secret' => '',
    ],
];
