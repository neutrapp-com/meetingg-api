<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers;

use Meetingg\Controllers\BaseController;
use Tests\Unit\AbstractUnitTest;
use Phalcon\Mvc\Controller;

class BaseControllerTest extends AbstractUnitTest
{
    public function testBaseInstance()
    {
        $instanceController = new BaseController();

        $this->assertTrue($instanceController instanceof Controller);
    }

    public function testIndex()
    {
        $instanceController = new BaseController();


        $this->assertIsArray($instanceController->index());
        $this->assertArrayHasKey('routes', $instanceController->index());
    }
}
