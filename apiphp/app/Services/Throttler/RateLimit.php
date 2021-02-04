<?php

namespace Meetingg\Services\Throttler;

class RateLimit
{
    /**
     * Number of successful hits.
     *
     * @var int
     */
    private $hits;

    /**
     * Number of remaining hits before getting banned.
     *
     * @var int
     */
    private $remaining;

    /**
     * Period for each refillment.
     *
     * @var int
     */
    private $period;

    /**
     * Maximum number of available hits for a full bucket.
     *
     * @var int
     */
    private $hitsPerPeriod;

    /**
     * @var bool
     */
    private $limited;

    /**
     * @var bool
     */
    private $warning;

    public function __construct(
        int $hits,
        int $remaining,
        int $period,
        int $hitsPerPeriod,
        bool $limited,
        bool $warning,
        int $limit = 0,
        float $warningLimit = 0
    ) {
        $this->hits = $hits;

        $this->remaining = $remaining;

        $this->period = $period;

        $this->hitsPerPeriod = $hitsPerPeriod;

        $this->limited = $limited;

        $this->warning = $warning;

        $this->limit = $limit;

        $this->warningLimit = $warningLimit;
    }

    /**
     * @return int
     */
    public function getHits(): int
    {
        return $this->hits;
    }

    /**
     * @return int
     */
    public function getRemaining(): int
    {
        return $this->remaining;
    }

    /**
     * @return int
     */
    public function getPeriod(): int
    {
        return $this->period;
    }

    /**
     * @return int
     */
    public function getHitsPerPeriod(): int
    {
        return $this->hitsPerPeriod;
    }

    /**
     * @return bool
     */
    public function isLimited(): bool
    {
        return $this->limited;
    }

    /**
     * @return bool
     */
    public function isWarning(): bool
    {
        return $this->warning;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'hits' => $this->getHits(),
            'remaining' => $this->getRemaining(),
            'period' => $this->getPeriod(),
            'hits_per_period' => $this->getHitsPerPeriod(),
            'warning' => $this->isWarning(),
            'limited' => $this->isLimited()
        ];
    }

    /**
     * Get the value of limit
     */
    public function getLimit() : int
    {
        return $this->limit;
    }
    
    /**
     * Get remaining waiting time
     *
     * @return float
     */
    public function getRemainingTime() : float
    {
        return floatval($this->isLimited() ? $this->warningLimit : 0);
    }
}
