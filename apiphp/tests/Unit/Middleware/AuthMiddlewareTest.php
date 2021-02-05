<?php
declare(strict_types=1);

namespace Tests\Unit\Exception;

use ReflectionClass;
use DateTimeImmutable;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Signer\Key\InMemory;

use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Events\Event;

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
        extract($this->newMicroApp());
        $diFactory = $this->initConfigJWT($diFactory);
        $event = new Event("beforeExecuteRoute", $app);

        $instance = new class() extends AuthMiddleware {
            public $_routeName = "profile";
            public $_authorized = true;


            public function getRouteName(Micro $app): ?string
            {
                return $this->_routeName;
            }
        
            protected function authorize(Micro $app) : bool
            {
                return  $this->_authorized;
            }
        };
         
        $instance->_routeName  = "index";
        $this->assertSame('index', $instance->getRouteName($app));
        $this->assertSame(true, $instance->beforeExecuteRoute($event, $app));

        
        $instance->_routeName  = "profile";
        $this->assertSame('profile', $instance->getRouteName($app));
        try {
            $instance->beforeExecuteRoute($event, $app);
        } catch (\Exception $e) {
            $this->assertSame($e->getMessage(), "Please authorize with valid API token");
        }
 
        $_SERVER['REQUEST_METHOD'] = "POST";
        $_SERVER['CONTENT_TYPE'] = "text/plain";

        $instance->_routeName = "index";
        $this->assertSame('index', $instance->getRouteName($app));
        $this->expectExceptionMessage("Only app/json is accepted for Content-Type in POST requests");
        $this->assertSame(true, $instance->beforeExecuteRoute($event, $app));
    }

    public function testAuthorize() : void
    {
        $token = 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';
        
        extract($this->newMicroApp());

        $instance = new AuthMiddleware();
        $class = new ReflectionClass(AuthMiddleware::class);
        $method = $class->getMethod('authorize');
        $method->setAccessible(true);

        try {
            $this->assertTrue($method->invoke($instance, $app));
        } catch (\Exception $e) {
            $this->assertSame("Service 'jwt' wasn't found in the dependency injection container", $e->getMessage());
        }
        
        $diFactory = $this->initConfigJWT($diFactory);

        $this->assertFalse($method->invoke($instance, $app));
        $_SERVER["HTTP_AUTHORIZATION"]  = $token;

        $this->expectExceptionMessage("The token violates some mandatory constraints, details:\n- The token was not issued by the given issuers\n- The token is not allowed to be used by this audience\n- Token signer mismatch");
        $this->assertFalse($method->invoke($instance, $app));
    }

    public function testAuthorizeJWTTokens() : void
    {
        extract($this->newMicroApp());

        $diFactory = $this->initConfigJWT($diFactory);
        $token = $this->generateJWTToken($app, $diFactory->getShared('jwt')['config'])->toString();

        // verify headers method
        $_SERVER["HTTP_AUTHORIZATION"]  =  "Bearer $token";
        $this->assertSame($app->request->getHeader("Authorization"), $_SERVER['HTTP_AUTHORIZATION']);

        // assert valid token
        $this->assertTrue($this->checkTokenValidation($app, $token));

        // assert invalid token
        $Invalidtoken = $this->generateJWTToken($app, $diFactory->getShared('jwt')['config'], "-1 hours")->toString();
        $this->assertFalse($this->checkTokenValidation($app, $Invalidtoken));
    }

    public function checkTokenValidation(Micro $app, string $token = null) : bool
    {
        $instance = new AuthMiddleware();
        $class = new ReflectionClass(AuthMiddleware::class);
        $method = $class->getMethod('authorize');
        $method->setAccessible(true);

        $_SERVER["HTTP_AUTHORIZATION"]  =  "Bearer $token";
        $this->assertSame($app->request->getHeader("Authorization"), $_SERVER['HTTP_AUTHORIZATION']);
        $valid  = false;
        try {
            $valid = $method->invoke($instance, $app);
        } catch (\Exception $e) {
        }

        return $valid;
    }
    
    public function testGetRouteName() : void
    {
        extract($this->newMicroApp());
        $auth = new AuthMiddleware();

        try {
            $auth->getRouteName($app);
        } catch (\Exception $e) {
            $this->assertSame("Router is missing to get route name", $e->getMessage());
        }

        $this->assertSame("profile", $auth->getRouteName(
            new class extends Micro {
                public $router;
                public function __construct()
                {
                    $this->router = new class {
                        public function getMatchedRoute()
                        {
                            return new class {
                                public function getName()
                                {
                                    return 'profile';
                                }
                            };
                        }
                    };
                }
            }
        ));
    }


    /**
     * Helper Methods
     *
     */
    private function newMicroApp() : array
    {
        $diFactory = new FactoryDefault();
        $app = new Micro($diFactory);

        return ['diFactory' => $diFactory , 'app' => $app];
    }

    private function initConfigJWT($diFactory) : FactoryDefault
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

    public function generateJWTToken(Micro $app, Configuration $config, string $expire = '+1 hour') : object
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
}
