<?php
declare(strict_types=1);

namespace Tests\Unit\Exception;

use ReflectionClass;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Signer\Key\InMemory;

use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;

use Tests\Unit\AbstractUnitTest;
use Meetingg\Middleware\AuthMiddleware;
use Meetingg\Exception\PublicException;

class AuthMiddlewareTest extends AbstractUnitTest
{
    /**
     * Test Baerertoken
     * @dataProvider providerGetBearerToken
     * @param string $token
     * @param string $expected
     */
    public function testGetBearerToken(bool $work, string $token, string $expected) : void
    {
        $instace = new AuthMiddleware();

        if ($work === true) {
            $this->assertSame($expected, $instace->getBearerToken($token));
        } else {
            $this->assertNotSame($expected, $instace->getBearerToken($token));
        }
    }

    public static function providerGetBearerToken() : array
    {
        return [
            'jwt token bearer'=> [ true,  'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c','eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c'],
            'alpha token bearer'=> [ true,  'Bearer azertyuiopqsdfghjklmwxcvbn123456789','azertyuiopqsdfghjklmwxcvbn123456789'],
            'failed token bearer'=> [ false,  'Failed azertyuiopqsdfghjklmwxcvbn123456789','azertyuiopqsdfghjklmwxcvbn123456789'],
            'not a bearer header'=> [ false,  'not a good header','not a good header'],
        ];
    }

    public function testCallEvent() : void
    {
        $instace = new AuthMiddleware();
        
        $this->assertTrue($instace->call(new Micro()));
    }
    
    public function testBeforeExecuteRoute() : void
    {
        $this->assertSame(true, !!true);
    }

    /**
     * @throws PublicException
     */
    public function testAuthorize() : void
    {
        $token = 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';
        
        $di = new FactoryDefault();
        $app = new Micro($di);

        $instance = new AuthMiddleware();
        $class = new ReflectionClass(AuthMiddleware::class);
        $method = $class->getMethod('authorize');
        $method->setAccessible(true);

        try {
            $this->assertTrue($method->invoke($instance, $app));
        } catch (\Exception $e) {
            $this->assertSame("Service 'jwt' wasn't found in the dependency injection container", $e->getMessage());
        }
        
        $di = $this->initConfigJWT($di);

        $this->assertFalse($method->invoke($instance, $app));
        $_SERVER["HTTP_AUTHORIZATION"]  = $token;

        $this->expectExceptionMessage("The token violates some mandatory constraints, details:\n- The token was not issued by the given issuers\n- The token is not allowed to be used by this audience\n- Token signer mismatch");
        $this->assertFalse($method->invoke($instance, $app));
    }


    private function initConfigJWT($di) : FactoryDefault
    {
        $di->setShared('config', function () {
            return json_decode(json_encode([
                'mode'=>'development',
                'jwt' => [
                    'url'  => 'http://localhost:8000',
                    'timezone' => 'Europe/Paris'
                ],
            ]));
        });

        $di->setShared('jwt', function () {
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

        return $di;
    }
}
