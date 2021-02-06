<?php

declare(strict_types=1);

namespace Tests\Unit;

define('ENV_PATH', __DIR__);

use DateTimeImmutable;

use Phalcon\Di;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Incubator\Test\PHPUnit\UnitTestCase;
use PHPUnit\Framework\IncompleteTestError;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Signer\Key\InMemory;

abstract class AbstractUnitTest extends UnitTestCase
{
    private bool $loaded = false;

    protected function setUp() : void
    {
        parent::setUp();

        $di = new FactoryDefault();//

        Di::reset();
        Di::setDefault($di);

        $this->config = require('config/config.php');
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

        $config = $this->config;

        $di->setShared('config', function () use ($config) {
            return $config;
        });

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

    
    protected function initServiceJWT($diFactory) : FactoryDefault
    {
        $diFactory->setShared('jwt', function () {
            $secretString = $this->getConfig()->jwt->secretkey;
            $secretKey = InMemory::base64Encoded($secretString);
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
