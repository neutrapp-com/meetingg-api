<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Tests\Unit\AbstractUnitTest;
use Meetingg\Models\AbstractCacheable;

class AbstractCacheableTest extends AbstractUnitTest
{
    protected $abstractClass;
    
    protected function setUp() : void
    {
        parent::setUp();
        // Create a new instance from the Abstract Class
        $this->abstractClass = new class extends AbstractCacheable {
            public static function generateCacheKey(array $parameters): string
            {
                return parent::generateCacheKey($parameters);
            }
        };
    }
    
    /**
     * @dataProvider cacheKeyExamplesProvider
     *
     * @param array $parameters
     * @param string $key
     * @return void
     */
    public function testGenerateCacheKey($parameters, $key)
    {
        $generateCacheKey = $this->abstractClass::generateCacheKey($parameters);

        $this->assertSame($generateCacheKey, $key);
    }


    public static function cacheKeyExamplesProvider()
    {
        return [
            'empty array parameters'=> [ [] , "d41d8cd98f00b204e9800998ecf8427e" ],
            'simple bind'=> [ ['test = ?0' , 'bind'=> ['testvalue']] , "1c298d7a5ff38bb922d0f7ed7caaefc1" ],
            'double bind diffirent binding'=> [ ['test = ?0 AND name = :name:' , 'bind'=> ['testvalue','name'=> 'name test']] , "978e4673ea7844e8d1999f8ffbca4bbd" ],
            'complecated bind with order'=> [
                    ['test = ?0 AND name = :name:' ,
                    'bind'=> ['testvalue','name'=> 'name test'],
                    'order'=>'name DESC'
                    ]
                    , "3ce8e80e1efb285aba1d16a6bae85e7a" ],
        ];
    }
}
