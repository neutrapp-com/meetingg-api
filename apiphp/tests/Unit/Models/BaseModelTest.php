<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Meetingg\Models\BaseModel;
use Tests\Unit\AbstractUnitTest;
use Phalcon\Mvc\Controller;

class BaseModelTest extends AbstractUnitTest
{
    /**
     * test get client ip
     *
     * @return void
     */
    public function testGetIp()
    {
        $ipExemple = '127.0.0.1';
        $instanceBaseModel = new BaseModel();

        $this->assertEquals($instanceBaseModel, $instanceBaseModel->setIp($ipExemple));
        $this->assertEquals($ipExemple, $instanceBaseModel->getIp());
    }

    /**
     * test before create
     *
     * @return void
     */
    public function testBeforeCreate()
    {
        $class = new BaseModel();
        $class->created_at = 0;

        $class->beforeCreate();
        $this->assertSame($class->created_at, 0);

        $newClass = new class() extends BaseModel {
            public int $created_at = 999;
            public ?string $created_ip = '0.0.0.0';
        
            public function getCreatedAt()
            {
                return $this->created_at;
            }
        };
        
        $newClass->setIp("10.1.1.15");
        $newClass->beforeCreate();
        $this->assertNotSame($newClass->getCreatedAt(), 999);
        $this->assertSame($newClass->created_ip, '10.1.1.15');
    }

    /**
     * Test before save
     *
     * @return void
     */
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

    /**
     * Test before delete
     *
     * @return void
     */
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

    /**
     * Test default schema name
     *
     * @return void
     */
    public function testSetDefaultSchema() : void
    {
        $newClass = new BaseModel;
        $newClass->setDefaultSchema();

        $this->assertSame($_ENV['DB_SCHEMA'] ?? "mgg", $newClass->getSchema());
    }

    /**
     * @dataProvider uuidValidationProvider
     *
     * @param string $uuid
     * @param boolean $valid
     * @return void
     */
    public function testValidUUID(string $uuid, bool $valid) : void
    {
        $this->assertSame($valid, BaseModel::validUUID($uuid));
    }


    public static function uuidValidationProvider() : array
    {
        return [
            [ 'd6fd3466-4e9f-4f2b-a33b-ed316003f8cf' , true  ],
            [ 'cc60891b-eb05-426b-9693-7df81fadf7a5' , true  ],
            [ 'a179cea0-68e3-11eb-9439-0242ac130002' , true  ],
            [ '0 259 458 694'  , false ],
            [ 'this is an invalide uuid'  , false ],
            [ '1-2-3-4-5-6-7-8-9-10-12'  , false ],
        ];
    }
}
