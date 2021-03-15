<?php
declare(strict_types=1);

namespace Meetingg\Helpers;

use DateTimeImmutable;
use Lcobucci\JWT\ClaimsFormatter;
use Lcobucci\JWT\Encoding\MicrosecondBasedDateConversion;
use Lcobucci\JWT\Encoding\UnifyAudience;
use Lcobucci\JWT\Encoding\UnixTimestampDates;
use Lcobucci\JWT\Token\RegisteredClaims;

class DateTimeFloatSerializer implements ClaimsFormatter
{
    /** @var list<ClaimsFormatter> */
    private array $formatters;

    public function __construct(ClaimsFormatter ...$formatters)
    {
        $this->formatters = $formatters;
    }
    
    public static function default(): self
    {
        return new self(new UnifyAudience(), new DateTimeFloatFormatter());
    }
    
    public static function withUnixTimestampDates(): self
    {
        return new self(new UnifyAudience(), new UnixTimestampDates());
    }
    
    /** @inheritdoc */
    public function formatClaims(array $claims): array
    {
        foreach ($this->formatters as $formatter) {
            $claims = $formatter->formatClaims($claims);
        }
    
        return $claims;
    }
}

class DateTimeFloatFormatter implements ClaimsFormatter
{
    /** @inheritdoc */
    public function formatClaims(array $claims): array
    {
        foreach (RegisteredClaims::DATE_CLAIMS as $claim) {
            if (! array_key_exists($claim, $claims)) {
                continue;
            }

            $claims[$claim] = (float) $this->convertDate($claims[$claim]);
        }

        return $claims;
    }

    /** @return int|string */
    private function convertDate(DateTimeImmutable $date)
    {
        $seconds      = $date->format('U');
        $microseconds = $date->format('u');

        if ((int) $microseconds === 0) {
            return (int) $seconds;
        }

        return $seconds . '.' . $microseconds;
    }
}
