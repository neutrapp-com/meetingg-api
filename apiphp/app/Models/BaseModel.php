<?php

namespace Meetingg\Models;

use DateTime;
use Phalcon\Mvc\Model;
use Phalcon\Security\Random;

use Meetingg\Http\StatusCodes;
use Meetingg\Exception\PublicException;
use Meetingg\Interfaces\SharedConstInterface;

class BaseModel extends Model implements SharedConstInterface
{

    /** @var ID_VALIDATOR */
    const ID_VALIDATOR = true;

    /**
     * Client IP
     *
     * @var string
     */
    protected $client_ip = null;

    /**
     * Default Schema Name
     *
     * @var string
     */
    protected $schemaName = "mgg";


    /**
     * Find First By Id : Validate UUID
     *
     * @param string $id
     * @return \Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirstById(string $id) :? \Phalcon\Mvc\ModelInterface
    {
        if (true === get_called_class()::ID_VALIDATOR && false === self::validUUID($id)) {
            throw new PublicException("invalid id", StatusCodes::HTTP_BAD_REQUEST);
        }
        
        return parent::findFirstById($id);
    }

    /**
     * Set Default Schema Name
     *
     * @return void
     */
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
        /**
         * Generate new UUID
         */
        $this->generateUUID();

        /**
         * Fill Meta Time
         */
        $selfClass = get_called_class();

        if (property_exists($selfClass, 'created_at')) {
            $this->created_at = self::getTime();
        }
        if (property_exists($selfClass, 'created_ip')) {
            $this->created_ip = $this->client_ip;
        }
    }
    
    /**
     * Before update , Save microtime into database
     *r
     * @return void
     */
    public function beforeUpdate() : void
    {
        $selfClass = get_called_class();

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
        $selfClass = get_called_class();

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
    public static function getTime() :? string
    {
        $date = new DateTime('now');

        return substr($date->format('Y-m-d H:i:s.u'), 0, 22);
    }

    /**
     * Validate an UUID
     *
     * @param string $uuid
     * @return boolean
     */
    public static function validUUID(string $uuid) : bool
    {
        $matches = preg_match('/^[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-f]{12}$/i', $uuid);
        return $matches >= 1;
    }

    /**
     * Set Model Status
     *
     * @param boolean $active
     * @return self
     */
    public function setActive(bool $active) : self
    {
        $this->status = true === $active ? self::ACTIVE : self::INACTIVE;
        return $this;
    }

    /**
     * Generate New UUID
     *
     * @return self
     */
    protected function generateUUID() : self
    {
        $class = get_called_class();
        
        if (true === property_exists($class, 'id')) {
            $this->id = (new Random)->uuid();
        }

        return $this;
    }
}
