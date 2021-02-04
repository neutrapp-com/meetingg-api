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
        throw new PublicException("You are being rate limited", StatusCodes::HTTP_TOO_MANY_REQUESTS);
        return false;
    }


    public function call(Micro $app)
    {
        return true;
    }
}
