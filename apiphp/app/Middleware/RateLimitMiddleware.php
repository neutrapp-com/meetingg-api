<?php

namespace Meetingg\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro\MiddlewareInterface;

use Meetingg\Http\StatusCodes;
use Meetingg\Exception\PublicException;
use Meetingg\Services\Throttler\RateLimit;

class RateLimitMiddleware implements MiddlewareInterface
{
    protected RateLimit $rateLimit;

    public function beforeExecuteRoute(Event $event, Micro $app)
    {
        if ($this->isLimited($app)) {
            throw new PublicException("You are being rate limited", StatusCodes::HTTP_TOO_MANY_REQUESTS, [
                "X-RateLimit-Limit" => $this->rateLimit->getLimit(),
                "X-RateLimit-Remaining" => $this->rateLimit->getRemaining(),
            ]);
        }

        return true;
    }

    private function isLimited(Micro $app) : bool
    {
        $throttler = $app->getService('throttler');
        $this->rateLimit = $throttler->consume($app->request->getClientAddress());
        return $this->rateLimit->isLimited();
    }

    public function call(Micro $app)
    {
        return true;
    }
}
