<?php

namespace Meetingg\Models;

/**
 * Abstract Cacheable Model
 */
abstract class AbstractCacheable extends BaseModel
{
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
    public static function findFirst($parameters = null) : ? \Phalcon\Mvc\ModelInterface
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
    protected static function checkCacheParameters($parameters = null) : array
    {
        if (null !== $parameters) {
            if (is_array($parameters) !== true) {
                $parameters = [$parameters];
            }

            if (isset($parameters['cache']) !== true) {
                $key = self::generateCacheKey($parameters);
                
                $parameters['cache'] = [
                    'key'      => $key,
                    'lifetime' => 300,
                ];
            }
        }

        return $parameters;
    }
}
