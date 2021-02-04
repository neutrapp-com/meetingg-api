<?php

namespace Meetingg\Services\Throttler;

interface ThrottlerInterface
{
    public function consume(string $meterId, int $warnThreshold = 0, int $numTokens = 1, int $time = null): RateLimit;

    public function isLimitWarning();

    public function isLimitExceeded();
}
