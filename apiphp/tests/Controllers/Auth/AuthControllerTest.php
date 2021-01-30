<?php

declare(strict_types=1);

namespace Tests\Controllers\Client;

use Tests\Unit\AbstractUnitTest;
use Meetingg\Controllers\BaseController;
use Meetingg\Controllers\Client\AuthController;

class AuthControllerTest extends AbstractUnitTest
{
    public function testBaseInstance()
    {
        $instanceController = new AuthController();

        $this->assertInstanceOf(BaseController::class, $instanceController);
    }

    public function testIndexAction()
    {
        $instanceController = new AuthController();

        $this->assertSame(
            $instanceController->index(),
            array(
             )
        );
    }
}
