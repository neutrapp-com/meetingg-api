<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use Meetingg\Models\AbstractCacheable;

class MockAbstractCacheable extends AbstractCacheable
{
    public $isMocked = false;

    public static function generateCacheKey(array $parameters): string
    {
        return self::generateCacheKey_Obj($parameters);
    }
    public static function checkCacheParameters($parameters = null): array
    {
        return parent::checkCacheParameters($parameters);
    }

    public function generateCacheKey_Obj($parameters, $mock = false)
    {
        return parent::generateCacheKey($parameters);
    }
}
