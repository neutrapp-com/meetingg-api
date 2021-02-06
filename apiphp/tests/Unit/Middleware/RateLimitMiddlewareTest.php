<?php
declare(strict_types=1);

namespace Tests\Unit\Exception;

use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Phalcon\Di\FactoryDefault;

use Tests\Unit\AbstractUnitTest;
use Meetingg\Middleware\RateLimitMiddleware;
use Meetingg\Services\Throttler\CacheThrottler;
use Meetingg\Services\Throttler\RateLimit;
use Phalcon\Cache;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Storage\Serializer\Json as JsonSerializer;

class RateLimitMiddlewareTest extends AbstractUnitTest
{
    public function testIsLimited()
    {
        extract($this->generateNewMicroApp());
        
        $diFactory = $this->initThrottlerService($diFactory);

        $instace = new class extends RateLimitMiddleware {
            public function isLimited(Micro $app) : bool
            {
                return parent::isLimited($app);
            }
        };

        $this->assertSame(false, $instace->isLimited($app));
    }

    public function testBeforeExecuteRoute() : void
    {
        extract($this->generateNewMicroApp());

        $diFactory = $this->initThrottlerService($diFactory);

        $event = new Event("beforeExecuteRoute", $app);

        $instance = new class() extends RateLimitMiddleware {
            protected RateLimit $rateLimit;
            public $_isLimited = null;


            public function isLimited(Micro $app) : bool
            {
                if (!is_null($this->_isLimited)) {
                    return $this->_isLimited;
                }

                $isLimited = parent::isLimited($app);
                var_dump($isLimited ? 'limitedddddddddd' : 'no');

                return $isLimited;
            }

            public function setRateLimit(RateLimit $rateLimit) : void
            {
                $this->rateLimit = $rateLimit;
            }
        };

        $instance->_isLimited = false;
        $this->assertSame(true, $instance->beforeExecuteRoute($event, $app));

        $instance->_isLimited = true;
        $instance->setRateLimit(new RateLimit(1, 0, 1, 1, true, true, 10, 50));
        try {
            $called = $instance->beforeExecuteRoute($event, $app);
            $this->assertNull($called);
        } catch (\Exception $e) {
            $this->assertSame($e->getMessage(), "You are being rate limited");
        }
    }


    public function testCallEvent() : void
    {
        $instace = new RateLimitMiddleware();
        
        $this->assertTrue($instace->call(new Micro()));
    }

    private function initThrottlerService(FactoryDefault $diFactory) : FactoryDefault
    {
        /**
         * Throttler : Rate Limiting
         */
        $diFactory->setShared('throttler', function () use ($diFactory) {
            $configs =  $diFactory->getConfig()->throttler;
            return new CacheThrottler($diFactory->get($configs->cacheService ?? 'cache'), $configs->toArray());
        });
        /**
         * Models Caching
         */

        $diFactory->setShared(
            'cache',
            function () {
                $config = $this->getConfig();
                $cacheAdapter = $config->cache->adapter;

                $jsonSerializer = new JsonSerializer();

                if (!$config->cache->options[$cacheAdapter]) {
                    throw new \Exception("Cache Adapter $cacheAdapter Options null");
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

        return $diFactory;
    }
}
