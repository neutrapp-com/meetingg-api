<?php

declare(strict_types=1);

namespace Tests\Unit\Services;
use Tests\Unit\AbstractUnitTest;
use Meetingg\Services\Throttler\CacheThrottler;
use Phalcon\Cache as Cache;

class CacheThrottlerTest extends AbstractUnitTest 
{
    public function testCache() 
    {
        $cache = new Cache();
        $instance = new CacheThrottler($cache, [0,1,2,3]);
 
    } 
    
}