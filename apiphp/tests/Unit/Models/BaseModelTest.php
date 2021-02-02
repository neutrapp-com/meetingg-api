<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Meetingg\Models\BaseModel;
use Tests\Unit\AbstractUnitTest;
use Phalcon\Mvc\Controller;

class BaseModelTest extends AbstractUnitTest
{

    public function testGetIp()
    {
        $ipExemple = '127.0.0.1';
        $instanceBaseModel = new BaseModel();

        $this->assertEquals($instanceBaseModel, $instanceBaseModel->setIp($ipExemple));
        $this->assertEquals($ipExemple, $instanceBaseModel->getIp());
    }

    public function testBeforeCreate()
    {
        $class = new BaseModel();
        $class->created_at = 0;

        $class->beforeCreate();
        $this->assertSame($class->created_at,0);

        $newClass = new class() extends BaseModel {
            public int $created_at = 999;
            public ?string $created_ip = '0.0.0.0';
        
            public function getCreatedAt(){
                return $this->created_at;
            }
        };
        
        $newClass->setIp("10.1.1.15");
        $newClass->beforeCreate();
        $this->assertNotSame($newClass->getCreatedAt(), 999);
        $this->assertSame($newClass->created_ip, '10.1.1.15');

    }

    public function testbeforeSave()
    {
        $newClass = new class() extends BaseModel {
            public int $updated_at = 888;
            public ?string $updated_ip = '0.0.0.0';
        };
        
        $newClass->setIp("10.1.1.15");
        $newClass->beforeSave();
        $this->assertNotSame($newClass->updated_at, 888);
        $this->assertSame($newClass->updated_ip, '10.1.1.15');

    }

    public function testbeforeDelete()
    {
        $newClass = new class() extends BaseModel {
            public int $deleted_at = 777;
            public ?string $deleted_ip = '0.0.0.0';
        };
        
        $newClass->setIp("10.1.1.15");
        $newClass->beforeDelete();
        $this->assertNotSame($newClass->deleted_at, 777);
        $this->assertSame($newClass->deleted_ip, '10.1.1.15');

    }

}


