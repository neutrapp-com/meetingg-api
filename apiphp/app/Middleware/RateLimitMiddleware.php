<?php

namespace Meetingg\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;

use Meetingg\Http\StatusCodes;
use Meetingg\Exception\PublicException;
use Meetingg\Services\Throttler\RateLimit;

class RateLimitMiddleware extends BaseMiddleware
{
    protected RateLimit $rateLimit;

    /**
     * Construct ; Generate Default Rate Limit
     */
    public function __construct()
    {
        $this->rateLimit = new RateLimit(0, 0, 0, 0, false, false, 0, 0);
    }


    /**
     * Before Execute Route Event
     *
     * @param Event $event
     * @param Micro $app
     * @return void
     */
    public function beforeExecuteRoute(Event $event, Micro $app)
    {
        $isLimited = $this->isLimited($app);

        if ($isLimited) {
            throw new PublicException(
                "You are being rate limited",
                StatusCodes::HTTP_TOO_MANY_REQUESTS,
                [
                    "X-RateLimit-Limit" => $this->rateLimit->getLimit(),
                    "X-RateLimit-Remaining" => $this->rateLimit->getRemaining(),
                ],
                [
                    'retry_after' => $this->rateLimit->getRemainingTime()
                ]
            );
        }

        return true;
    }


    /**
     * Is Rate Limited
     *
     * @param Micro $app
     * @return boolean
     */
    public function isLimited(Micro $app) : bool
    {
        $throttler = $app->getService('throttler');
        $this->rateLimit = $throttler->consume($app->request->getClientAddress());
        return $this->rateLimit->isLimited();
    }
}
