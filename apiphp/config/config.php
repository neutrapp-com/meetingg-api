<?php

use Dotenv\Dotenv;
use Phalcon\Config;

/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', $_ENV['BASE_PATH'] ?? realpath(dirname(__FILE__) . '/..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

require_once(dirname(__DIR__) . '/vendor/autoload.php');

(Dotenv::createImmutable(dirname(__DIR__)))->load();

$config =  new Config([
    'database' => [
        'adapter'    => $_ENV['DB_ADAPTER'] ?? 'Mysql',
        'host'       => $_ENV['DB_HOST'] ?? 'localhost',
        'username'   => $_ENV['DB_USER'] ?? 'root',
        'password'   => $_ENV['DB_PASS'] ?? '',
        'dbname'     => $_ENV['DB_NAME'] ?? 'test',
        'schema'     => $_ENV['DB_SCHEMA'] ?? 'test',
        'port'       => intval($_ENV['DB_PORT'] ?? 3308),
    ],

    'cache'=>[
        'adapter'=> $_ENV['CACHE_ADAPTER'] ?? 'Php',
        'options'=>[
            'Redis'=>[
                'defaultSerializer' => 'Php',
                'lifetime'          => $_ENV['CACHE_REDIS_LIFETIME'] ??7200,
                'host'              => $_ENV['CACHE_REDIS_HOST'] ?? '0.0.0.0',
                'port'              => $_ENV['CACHE_REDIS_PORT'] ?? 6379,
                'index'             => 1,
            ],
            'apcu'=>[
                'defaultSerializer' => 'Php',
                'lifetime'          => $_ENV['CACHE_APCU_LIFETIME'] ?? 7200,
            ],
            'Stream'=>[
                'defaultSerializer' => 'Php',
                'storageDir' => $_ENV['CACHE_APCU_STORAGEDIR'] ?? BASE_PATH . '/storage/cache/shared/',
            ]
        ]
    ],

    'application' => [
        'modelsDir'      => APP_PATH . '/Models/',
        'controllersDir' => APP_PATH . '/Controllers/',
        'migrationsDir'  => BASE_PATH . '/resources/migrations/',
        'viewsDir'       => BASE_PATH . '/resources/views/',
        'baseUri'        => '/',
    ]
]);


return $config;