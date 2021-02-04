<?php

declare(strict_types=1);

namespace Tests\Unit\Services;
use Tests\Unit\AbstractUnitTest;
use Meetingg\Services\Throttler\RateLimit;

class RateLimitTest extends AbstractUnitTest
{

    public function testGetters() : void 
    {
        $instance = new RateLimit(0,1,2,3,true,true, 4,5.0);

        $this->assertSame(0, $instance->getHits());
        $this->assertSame(1, $instance->getRemaining());
        $this->assertSame(2, $instance->getPeriod());
        $this->assertSame(true, $instance->isLimited());
        $this->assertSame(3, $instance->getHitsPerPeriod());
        $this->assertSame(true, $instance->isWarning());
        $this->assertSame(4, $instance->getLimit());
        $this->assertSame(5.0, $instance->getRemainingTime());
    }

    public function testToArray() : void
    {
        $instance = new RateLimit(0,1,2,3,true,true, 4,5.0);

        $this->assertSame([
            'hits' => 0,
            'remaining' => 1,
            'period' => 2,
            'hits_per_period' => 3,
            'warning' => true, 
            'limited' => true
        ], $instance->toArray());
    }
} 