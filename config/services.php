<?php
declare(strict_types=1);

use Meetingg\Exception\PublicException;
use Meetingg\Http\StatusCodes;
use Phalcon\Cache;
use Phalcon\Url as UrlResolver;
use Phalcon\Mvc\View\Simple as View;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Storage\Serializer\Json as JsonSerializer;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Meetingg\Services\Throttler\CacheThrottler;

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
        foreach ($params as $key => $_val) {
            if (!in_array($key, ['host','port','username','password','dbname','schema'])) {
                unset($params[$key]);
            }
        }
    }

    try {
        $connection = new $class($params);
    } catch (\Exception $e) {
        // throw new public exception , let app handle it
        throw new PublicException("Database Connection Failed", StatusCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    return $connection;
});

/**
 * Models Caching
 */

$di->setShared(
    'cache',
    function () {
        $config = $this->getConfig();
        $cacheAdapter = $config->cache->adapter;

        $jsonSerializer = new JsonSerializer();

        if (!$config->cache->options[$cacheAdapter]) {
            throw new Exception("Cache Adapter $cacheAdapter Options null");
        }

        $cacheOptions = [
            'lifetime'          => 7200, // default 2h
            'serializer'        => $jsonSerializer // method of parse/save cache
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
    $secretKey = InMemory::plainText($this->getConfig()->jwt->secretkey);
    $config = Configuration::forSymmetricSigner(
        // You may use any HMAC variations (256, 384, and 512)
        new Sha256(),
        // replace the value below with a key of your own!
        $secretKey
        // You may also override the JOSE encoder/decoder if needed by providing extra arguments here
    );
    return [
        'key'=> $secretKey,
        'config'=> $config // instnace Configuration::class ready to use
    ];
});


/**
 * Throttler : Rate Limiting
 */
$di->setShared('throttler', function () use ($di) {
    $configs =  $di->getConfig()->throttler;
    return new CacheThrottler($di->get($configs->cacheService ?? 'cache'), $configs->toArray());
});
