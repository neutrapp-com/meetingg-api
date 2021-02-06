<?php

namespace Meetingg\Models;

use Phalcon\Mvc\Model;
use Meetingg\Interfaces\SharedConstInterface;

class BaseModel extends Model implements SharedConstInterface
{
    protected $client_ip = null;
    protected $schemaName = "dma";

    public function setDefaultSchema() : void
    {
        $this->setSchema($this->getDI()->config->database->schema ?? "mgg");
    }

    /**
     * Before Create , Save microtime into database
     *
     * @return void
     */
    public function beforeCreate() : void
    {
        $selfClass = get_class($this);

        if (property_exists($selfClass, 'created_at')) {
            $this->created_at = self::getTime();
        }
        if (property_exists($selfClass, 'created_ip')) {
            $this->created_ip = $this->client_ip;
        }
    }
    
    /**
     * Before Save , Save microtime into database
     *r
     * @return void
     */
    public function beforeSave() : void
    {
        $selfClass = get_class($this);

        if (property_exists($selfClass, 'updated_at')) {
            $this->updated_at = self::getTime();
        }
        if (property_exists($selfClass, 'updated_ip')) {
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
        $selfClass = get_class($this);

        if (property_exists($selfClass, 'deleted_at')) {
            $this->deleted_at = self::getTime();
        }
        if (property_exists($selfClass, 'deleted_ip')) {
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

    /**
     * Get Client Ip
     *
     * @return string
     */
    public function getIp() : string
    {
        return $this->client_ip;
    }

    /**
     * Get Time in seconds
     */
    public static function getTime() : int
    {
        return microtime(true);
    }
}
