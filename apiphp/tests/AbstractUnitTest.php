<?php

declare(strict_types=1);

namespace Tests\Unit;

use DateTimeImmutable;

use Phalcon\Di;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Incubator\Test\PHPUnit\UnitTestCase;
use PHPUnit\Framework\IncompleteTestError;

use Meetingg\Services\Throttler\CacheThrottler;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Signer\Key\InMemory;
use PHPUnit\Runner\Filter\Factory;

abstract class AbstractUnitTest extends UnitTestCase
{
    private bool $loaded = false;

    protected function setUp(): void
    {
        parent::setUp();

        $di = new FactoryDefault();//

        Di::reset();
        Di::setDefault($di);

        $this->loaded = true;
    }

    public function __destruct()
    {
        if (!$this->loaded) {
            throw new IncompleteTestError(
                "Please run parent::setUp()."
            );
        }
    }

    /**
     * Helper Methods
     *
     */
    protected function generateNewMicroApp() : array
    {
        $di = new FactoryDefault();
        $app = new Micro($di);

        return ['diFactory' => $di , 'app' => $app];
    }

    
    protected function generateJWTToken(Micro $app, Configuration $config, string $expire = '+1 hour') : object
    {
        $now   = new DateTimeImmutable();

        return $config->builder()
            // Configures the issuer (iss claim)
            ->issuedBy($app->config->jwt->url)
            // Configures the audience (aud claim)
            ->permittedFor($app->config->jwt->url)
            // Configures the time that the token was issue (iat claim)
            ->issuedAt($now)
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify($expire))
            // Configures a new claim, called "uid"
            ->withClaim('uid', 1)
            // Builds a new token
            ->getToken($config->signer(), $config->signingKey());
    }

    
    protected function initConfigJWT($diFactory) : FactoryDefault
    {
        $diFactory->setShared('config', function () {
            return json_decode(json_encode([
                'mode'=>'development',
                'jwt' => [
                    'url'  => 'http://localhost:8000',
                    'timezone' => 'Europe/Paris'
                ],
            ]));
        });

        $diFactory->setShared('jwt', function () {
            $secretKey = InMemory::base64Encoded("U0VDUkVU");
            $config = Configuration::forSymmetricSigner(
                // You may use any HMAC variations (256, 384, and 512)
                new Sha512(),
                // replace the value below with a key of your own!
                $secretKey
                // You may also override the JOSE encoder/decoder if needed by providing extra arguments here
            );
        
            return [
                'key'=> $secretKey,
                'config'=> $config
            ];
        });

        return $diFactory;
    }
}
