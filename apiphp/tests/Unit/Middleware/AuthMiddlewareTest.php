<?php
declare(strict_types=1);

namespace Tests\Unit\Exception;

use ReflectionClass;

use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;

use Tests\Unit\AbstractUnitTest;
use Meetingg\Middleware\AuthMiddleware;

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
        extract($this->generateNewMicroApp());
        $diFactory = $this->initServiceJWT($diFactory);
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
        
        extract($this->generateNewMicroApp());

        $instance = new AuthMiddleware();
        $class = new ReflectionClass(AuthMiddleware::class);
        $method = $class->getMethod('authorize');
        $method->setAccessible(true);

        try {
            $this->assertTrue($method->invoke($instance, $app));
        } catch (\Exception $e) {
            $this->assertSame("Service 'jwt' wasn't found in the dependency injection container", $e->getMessage());
        }
        
        $diFactory = $this->initServiceJWT($diFactory);

        $this->assertFalse($method->invoke($instance, $app));
        $_SERVER["HTTP_AUTHORIZATION"]  = $token;

        $this->expectExceptionMessage("The token violates some mandatory constraints, details:\n- The token was not issued by the given issuers\n- The token is not allowed to be used by this audience\n- Token signer mismatch");
        $authorized =  $this->assertFalse($method->invoke($instance, $app));
        
        $this->assertNotTrue($authorized);
        $this->assertNull($authorized);
    }

    public function testAuthorizeJWTTokens() : void
    {
        extract($this->generateNewMicroApp());

        $diFactory = $this->initServiceJWT($diFactory);
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
        extract($this->generateNewMicroApp());
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
}
