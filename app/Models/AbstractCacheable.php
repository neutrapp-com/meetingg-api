<?php

namespace Meetingg\Models;

/**
 * Abstract Cacheable Model
 */
abstract class AbstractCacheable extends BaseModel
{
    /** @var CACHE_LIFETIME */
    const CACHE_LIFETIME = 600; // 60 * 10 min
    /** @var CACHE_SERVICE */
    const CACHE_SERVICE = 'cache';

    /**
     * FindAll & Cache Save
     *
     * @param mixed $parameters
     * @return void
     */
    public static function find($parameters = null) : \Phalcon\Mvc\Model\ResultsetInterface
    {
        $parameters = self::checkCacheParameters($parameters);

        return parent::find($parameters);
    }

    /**
     * FindOne & Cache Save
     *
     * @param mixed $parameters
     * @return void
     */
    public static function findFirst($parameters = null): ? \Phalcon\Mvc\ModelInterface
    {
        $parameters = self::checkCacheParameters($parameters);

        return parent::findFirst($parameters);
    }


    /**
     * Method Generate Key By Data giving
     *
     * @param string $data
     * @return string
     */
    protected static function generateCacheKey(array $parameters) : string
    {
        $uniqueKey = [];

        foreach ($parameters as $key => $value) {
            if (true === is_scalar($value)) {
                $uniqueKey[] = $key . ':' . $value;
            } elseif (true === is_array($value)) {
                $uniqueKey[] = sprintf(
                    '%s:[%s]',
                    $key,
                    self::generateCacheKey($value)
                );
            }
        }

        return md5(join(',', $uniqueKey));
    }

    /**
     * Check Cache Parameters
     *
     * @param mixed $parameters
     * @return array
     */
    protected static function checkCacheParameters($parameters = null) :? array
    {
        if (is_array($parameters) !== true) {
            $parameters = [$parameters];
        }

        if (isset($parameters['cache']) !== true) {
            $key = self::generateCacheKey($parameters);
                
            $parameters['cache'] = [
                'key'      => self::getStartCacheKey(). $key,
                'service'  => self::CACHE_SERVICE,
                'lifetime' => self::CACHE_LIFETIME,
            ];
        }

        return $parameters;
    }


    /**
     * Get Start Cache Key
     *
     * @return string
     */
    public static function getStartCacheKey() : string
    {
        return substr(md5(self::class), 4);
    }


    /**
     * After Delete , remove cache
     *
     * @return void
     */
    public function afterDelete() : void
    {
        self::cleanCache();
    }


    /**
     * After Create , remove cache
     *
     * @return void
     */
    public function afterCreate() : void
    {
        self::cleanCache();
    }

    /**
     * After Update , remove cache
     *
     * @return void
     */
    public function afterSave() : void
    {
        self::cleanCache();
    }

    /**
     * Clean Cache
     *
     * @return void
     */
    public function cleanCache() : void
    {
        $this->getDi()->get(self::CACHE_SERVICE)->delete(self::getStartCacheKey());
    }
}
