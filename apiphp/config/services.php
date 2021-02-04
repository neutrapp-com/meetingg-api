<?php
declare(strict_types=1);

use Phalcon\Cache;
use Phalcon\Url as UrlResolver;
use Phalcon\Mvc\View\Simple as View;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Storage\Serializer\Json;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Signer\Key\InMemory;
use OakLabs\PhalconThrottler\RedisThrottler;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include BASE_PATH . "/config/config.php";
});

/**
 * Sets the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setViewsDir($config->application->viewsDir);
    return $view;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;

    $params = $config->database->toArray();

    if (strtolower($config->database->adapter) == 'postgresql') {
        unset($params['charset']);

        foreach ($params as $key=>$val) {
            if (!in_array($key, ['host','port','username','password','dbname','schema'])) {
                unset($params[$key]);
            }
        }
    }

    $connection = new $class($params);

    return $connection;
});

/**
 * Models Caching
 */

$di->setShared(
    'modelsCache',
    function () {
        $config = $this->getConfig();
        $cacheAdapter = $config->cache->adapter;

        $jsonSerializer = new Json();

        if (!$config->cache->options[$cacheAdapter]) {
            throw new Exception("Cache Adapter $cacheAdapter Options null");
        }
            
        
        $cacheOptions = [
            'lifetime'          => 7200,
            'serializer'        => $jsonSerializer
        ];
        
        $cacheOptions += $config->cache->options[$cacheAdapter]->toArray() ?? [] ;

        $serializerFactory = new SerializerFactory();
        
        $cacheAdapter = "\Phalcon\Cache\Adapter\\{$cacheAdapter}";
        $adapter = new $cacheAdapter($serializerFactory, $cacheOptions);
        
        return new Cache($adapter);
    }
);


/**
 * JWT Shared
 */
$di->setShared('jwt', function () {
    $secretKey = InMemory::base64Encoded($_ENV['JWT_SIGNER_KEY_BASE64BASE'] ?? "U0VDUkVU");
    $config = Configuration::forSymmetricSigner(
        // You may use any HMAC variations (256, 384, and 512)
        new Sha512(),
        // replace the value below with a key of your own!
        $secretKey
        // You may also override the JOSE encoder/decoder if needed by providing extra arguments here
    );

    return [
        'key'=> $secretKey,
        'config'=> $config
    ];
});


/**
 * Throttler : Rate Limiting
 */
$di->setShared('throttler', function () use ($di) {
    return new RedisThrottler($di->get('redis'), [
        'bucket_size'  => 20,
        'refill_time'  => 600, // 10m
        'refill_amount'  => 10
    ]);
});
