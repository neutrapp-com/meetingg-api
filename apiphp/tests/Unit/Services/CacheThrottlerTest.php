<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use Tests\Unit\AbstractUnitTest;
use Meetingg\Services\Throttler\CacheThrottler;
use Phalcon\Cache as Cache;
use Phalcon\Cache\Adapter\Stream;
use Phalcon\Storage\SerializerFactory;

class CacheThrottlerTest extends AbstractUnitTest
{
    public CacheThrottler $instance;

    protected function setUp():void
    {
        parent::setUp();
        
        $serializerFactory = new SerializerFactory();

        $options = [
            'defaultSerializer' => 'Php',
            'lifetime'          => 500,
            'storageDir'        => '../../storage/cache/',
        ];
        $stream = new Stream($serializerFactory, $options);
        $cache = new Cache($stream);

        
    
        $this->instance = new CacheThrottler($cache, ['refill_time' => 10]);
    }

    public function testGetters()
    {
        $this->assertSame(false, $this->instance->isLimitWarning());
        $this->assertSame(false, $this->instance->isLimitExceeded());
    }

    /**
     * @dataProvider providerEncodeKey
     */
    public function testEncodeKey(string $input, string $output) : void
    {
        $this->assertSame($output, $this->instance::encodeKey($input));
    }

    public static function providerEncodeKey() : array
    {
        return array(
            ["abc", "abc"],
            ["123%", "123."],
            ["12-3", "12.3"],
            ["12- 3", "12..3"],
            ["12-@3", "12..3"],
            ["Ajc^ L", "Ajc..L"]
        );
    }
}
