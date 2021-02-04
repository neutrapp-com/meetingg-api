<?php

namespace Meetingg\Services\Throttler;

interface ThrottlerInterface
{
    public function consume(string $meterId, int $numTokens = 1): RateLimit;

    public function isLimitWarning();

    public function isLimitExceeded();
}
