<?php

namespace Meetingg\Services\Throttler;

use Phalcon\Cache as Cache;

class CacheThrottler implements ThrottlerInterface
{
    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var bool
     */
    protected $limitExceeded;

    /**
     * @var bool
     */
    protected $limitWarning;

    /**
     * @param Cache $cacheAdapter
     * @param array $config
     */
    public function __construct(Cache $cache, array $config = [])
    {
        $this->config = array_merge([
            'bucket_size'  => 20,
            'refill_time'  => 600, // 10m
            'refill_amount'  => 10,
            'warning_limit' => 1
        ], $config);

        $this->cache = $cache;
    }

    /**
     * @return bool
     */
    public function isLimitWarning(): bool
    {
        return $this->limitWarning;
    }

    /**
     * @return bool
     */
    public function isLimitExceeded(): bool
    {
        return $this->limitExceeded;
    }

    /**
     * @param string $meterId
     * @param int $numTokens
     *
     * @return RateLimit
     */
    public function consume(string $meterId, int $numTokens = 1): RateLimit
    {
        $this->limitWarning = false;

        $this->limitExceeded = false;

        // Build the cache key
        $key = self::encodeKey(sprintf('rate_limiter:%s', $meterId));
        // Retrieve the bucket
        $bucket = $this->retrieveBucket($key);

        // Refill the value
        ['new_value' => $newValue, 'refill_count' => $refillCount] = $this->refillBucket($bucket);

        // If still <= 0, it's rate limited
        $newValue -= $numTokens;
        if ($newValue < 0) {
            $this->limitExceeded = true;
            $this->limitWarning = true;
        }

        if ($newValue <= $this->config['warning_limit']) {
            $this->limitWarning = true;
        }

        // Compute Last Update
        $newLastUpdate = min(
            time(),
            $bucket['last_update'] + $refillCount * $this->config['refill_time']
        );

        // Update cache && Expiry time
        $this->cache->set(
            $key,
            [
                'value' => $newValue,
                'last_update' => $newLastUpdate
            ],
            $this->getNewExpiryTime()
        );

        return new RateLimit(
            (int)round(($this->config['bucket_size']) - $newValue) / $numTokens,
            max(0, (int)round(($newValue / $numTokens))),
            $this->config['refill_time'],
            (int)ceil($this->config['bucket_size'] / $numTokens),
            $this->isLimitExceeded(),
            $this->isLimitWarning(),
            $this->config['bucket_size'] ?? 0,
            ($newLastUpdate + ($this->config['refill_time']) * (-$newValue)) - time()
        );
    }

    /**
     * If the bucket does not exist, it is created
     *
     * @param string $key
     * @return array
     */
    protected function retrieveBucket(string $key): array
    {
        $bucket =  array_merge(
            ['value' => $this->config['bucket_size'] , 'last_update' => time()],
            $this->cache->get($key) ?: []
        );
        return $bucket;
    }

    /**
     * Refull the bucket and return the new value.
     *
     * @param array $bucket
     * @return array
     */
    protected function refillBucket(array $bucket): array
    {
        // Check the refill count
        $refillCount = (int)floor((time() - $bucket['last_update']) / $this->config['refill_time']);

        // Refill the bucket
        return [
            'new_value' => min(
                $this->config['bucket_size'],
                $bucket['value'] + $refillCount * $this->config['refill_amount']
            ),
            'refill_count' => $refillCount
        ];
    }

    /**
     * @return integer
     */
    protected function getNewExpiryTime(): int
    {
        return time() +
            ((1 + (int)ceil($this->config['bucket_size'] / $this->config['refill_amount']))
                * $this->config['refill_time']);
    }

    /**
     * Encode Key to Cache-KeY
     *
     * @param string $string
     * @return string
     */
    public static function encodeKey(string $key) : string
    {
        return preg_replace("/[^A-Za-z0-9?! ]/", ".", $key);
    }
}
