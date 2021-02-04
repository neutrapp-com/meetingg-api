<?php

namespace Meetingg\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro\MiddlewareInterface;

use Meetingg\Http\StatusCodes;
use Meetingg\Exception\PublicException;

class RateLimitMiddleware implements MiddlewareInterface
{
    public function beforeExecuteRoute(Event $event, Micro $app)
    {
        if ($this->isLimited($app)) {
            throw new PublicException("You are being rate limited", StatusCodes::HTTP_TOO_MANY_REQUESTS);
        }

        return true;
    }

    private function isLimited(Micro $app) : bool
    {
        $throttler = $app->getService('throttler');
        $rateLimit = $throttler->consume($app->request->getClientAddress());

        $isLimited=  $rateLimit->isLimited();
        print_r($rateLimit);

        return $isLimited;
    }

    public function call(Micro $app)
    {
        return true;
    }
}
