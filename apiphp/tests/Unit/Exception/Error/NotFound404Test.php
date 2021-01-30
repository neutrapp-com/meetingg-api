<?php

namespace Tests\Unit\Exception;

use Meetingg\Exception\Error\NotFound404;
use Tests\Unit\AbstractUnitTest;

class NotFound404Test extends AbstractUnitTest
{
    public function testMessageNotFound404(): void
    {
        $message = "Route does not exist";
        $exception = new NotFound404();
        $this->assertSame($exception->getMessage(), $message);
        $this->assertSame(get_class($exception), NotFound404::class);
    }
    
    /**
     * Test thrown public exception
     */
    public function testThrowNotFound404(): void
    {
        $exception = new NotFound404("hello");
        $this->expectException(NotFound404::class);
        throw $exception;
    }
}
