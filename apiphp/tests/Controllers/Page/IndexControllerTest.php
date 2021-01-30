<?php

declare(strict_types=1);

namespace Tests\Controllers\Page;

use Tests\Unit\AbstractUnitTest;
use Meetingg\Controllers\BaseController;
use Meetingg\Controllers\Page\IndexController;

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

        $this->assertSame(
            $instanceController->index(),
            array()
        );
    }
}
