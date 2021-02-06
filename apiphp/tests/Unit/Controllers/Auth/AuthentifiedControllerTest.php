<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers\Auth;

use Tests\Unit\AbstractUnitTest;
use Meetingg\Controllers\BaseController;
use Meetingg\Controllers\Auth\AuthentifiedController;

class AuthentifiedControllerTest extends AbstractUnitTest
{
    public function testBaseInstance()
    {
        $instanceController = new AuthentifiedController();
        $this->assertInstanceOf(BaseController::class, $instanceController);
    }
}
