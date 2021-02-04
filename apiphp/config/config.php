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
    'mode' => $_ENV['APP_ENV'] ?? 'production',
    
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
        'adapter'=> $_ENV['CACHE_ADAPTER'] ?? 'Stream',
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
    ],

    'jwt' => [
        'url'  =>
        (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://")
        . $_SERVER['HTTP_HOST'] ,
        'timezone' => 'Europe/Paris'
    ],

    'throttler' => [
        'enable'=> true,
        'cacheSercice' => 'cache',
        'bucket_size'  => intval($_ENV['RATE_LIMITING_BUCKET_SIZE'] ?? 30), // the number of allowed hits in the period of time of reference
        'refill_time'  => intval($_ENV['RATE_LIMITING_REFILL_TIME'] ?? 5), // the amount of time after that the counter will completely or partially reset (1m)
        'refill_amount'  => intval($_ENV['RATE_LIMITING_REFILL_AMOUNT'] ?? 10), // the number of hits to be reset every time the refill_time passes
    ]
]);


return $config;
