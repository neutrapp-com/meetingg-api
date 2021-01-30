<?php

declare(strict_types=1);

namespace Tests\Unit\Exception;

use Meetingg\Exception\PublicException;
use Tests\Unit\AbstractUnitTest;

class PublicExceptionTest extends AbstractUnitTest
{
    public function testMessagePublicException(): void
    {
        $message = "Test Message Public";
        $exception = new PublicException($message);
        $this->assertSame($exception->getMessage(), $message);
        $this->assertSame(get_class($exception), PublicException::class);
    }
    
    /**
     * Test thrown public exception
     */
    public function testThrowPublicException(): void
    {
        $exception = new PublicException("hello");
        $this->expectException(PublicException::class);
        throw $exception;
    }
}
