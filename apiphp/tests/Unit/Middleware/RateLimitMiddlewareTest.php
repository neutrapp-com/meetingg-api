<?php
declare(strict_types=1);

namespace Tests\Unit\Exception;

use ReflectionClass;
use DateTimeImmutable;

use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Events\Event;

use Tests\Unit\AbstractUnitTest;
use Meetingg\Middleware\RateLimitMiddleware;
use Meetingg\Exception\PublicException;

class RateLimitMiddlewareTest extends AbstractUnitTest
{
    // public function testIsLimited()
    // {
    //     extract($this->generateNewMicroApp());
        
    //     $instace = new class extends RateLimitMiddleware {
    //         public function isLimited(Micro $app) : bool
    //         {
    //             return parent::isLimited($app);
    //         }
    //     };

    //     $this->assertSame(true, $instace->isLimited($app));
    // }
}
