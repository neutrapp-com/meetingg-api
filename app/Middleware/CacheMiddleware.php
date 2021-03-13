<?php

namespace Meetingg\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;

class CacheMiddleware extends BaseMiddleware
{
    /** @var CACHE_LIFETIME */
    const CACHE_LIFETIME = 86400;

    /**
     * beforeExecuteRoute
     *
     * @param Event $event
     * @param Micro $app
     * @return void
     */
    public function beforeExecuteRoute(Event $event, Micro $app)
    {
        if (true === $this->matchRoute($app, 'cached')) {
            $app->response->setHeader('Cache-Control', 'max-age='. self::CACHE_LIFETIME);
        }
        return true;
    }
}
