<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers;

use Tests\Unit\AbstractUnitTest;
use Meetingg\Controllers\BaseController;
use Meetingg\Controllers\IndexController;

class IndexControllerTest extends AbstractUnitTest
{
    public function testBaseInstance()
    {
        $instanceController = new IndexController();

        $this->assertInstanceOf(BaseController::class, $instanceController);
    }

    public function testReturnIndexAction()
    {
        $instanceController = new IndexController();

        $index = $instanceController->index();
        $this->assertArrayHasKey("routes", $index);
        $this->assertIsArray($index["routes"]);
    }
}
