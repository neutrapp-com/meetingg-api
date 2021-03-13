<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers\Auth;

use Tests\Unit\AbstractUnitTest;
use Meetingg\Controllers\User\ProfileController;
use Meetingg\Controllers\Auth\AuthentifiedController;

class ProfileControllerTest extends AbstractUnitTest
{
    private ProfileController $instance;

    protected function setUp() : void
    {
        parent::setUp();

        $this->instance = new ProfileController();
    }
    public function testBaseInstance()
    {
        $this->assertInstanceOf(AuthentifiedController::class, $this->instance);
    }

    public function testMethods() : void
    {
        $this->assertIsArray($this->instance->index());
    }
}
