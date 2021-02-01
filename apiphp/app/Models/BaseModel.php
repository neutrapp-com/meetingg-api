<?php

namespace Meetingg\Models;

use Phalcon\Mvc\Model;
use Meetingg\Interfaces\SharedConstInterface;

class BaseModel extends Model implements SharedConstInterface
{
    protected $client_ip = null;
    protected $schemaName = "dma";

    /**
     * Before Create , Save microtime into database
     *
     * @return void
     */
    public function beforeCreate() : void
    {
        if (property_exists(self::class, 'created_at')) {
            $this->created_at = microtime();
        }
        if (property_exists(self::class, 'created_ip')) {
            $this->created_ip = $this->client_ip;
        }
    }

    /**
     * Before Save , Save microtime into database
     *
     * @return void
     */
    public function beforeSave() : void
    {
        if (property_exists(self::class, 'updated_at')) {
            $this->updated_at = microtime();
        }
        if (property_exists(self::class, 'updated_ip')) {
            $this->updated_ip = $this->client_ip;
        }
    }

    /**
     * Before Delete , Save microtime into database
     *
     * @return void
     */
    public function beforeDelete() : void
    {
        if (property_exists(self::class, 'deleted_at')) {
            $this->deleted_at = microtime();
        }
        if (property_exists(self::class, 'deleted_ip')) {
            $this->deleted_ip = $this->client_ip;
        }
    }

    /**
     * Set Client Ip
     *
     * @param String $clientIp
     * @return self
     */
    public function setIp(string $clientIp) : self
    {
        $this->client_ip = $clientIp;
        return $this;
    }
}
