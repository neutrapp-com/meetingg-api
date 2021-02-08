<?php

namespace Meetingg\Middleware;

use Exception;
use DateTimeZone;
use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro\MiddlewareInterface;

use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

use Meetingg\Models\User;
use Meetingg\Http\StatusCodes;
use Meetingg\Exception\PublicException;

class AuthMiddleware implements MiddlewareInterface
{
    protected Micro $app;

    public function beforeExecuteRoute(Event $event, Micro $app)
    {
        $authorizeExceptions = [
            'index', 'login', 'register', 'forgetpassword', 'public'
        ];
        
        $routeName = $this->getRouteName($app);

        if (!in_array($routeName, $authorizeExceptions)) {
            $authorization = $this->authorize($app);
            
            if (is_null($authorization) !== false) {
                $app->response->setStatusCode(401, StatusCodes::HTTP_UNAUTHORIZED);
                throw new PublicException("Please authorize with valid API token");
                return false;
            }

            $app->getDI()->setShared('user', function () use ($authorization) {
                return User::findFirstById($authorization->claims()->get('uid'));
            });
        }

        if (in_array($app->request->getMethod(), ['POST', 'PUT']) and $app->request->getHeader('Content-Type') != 'application/json') {
            $app->response->setStatusCode(400, StatusCodes::HTTP_BAD_REQUEST);
            throw new PublicException("Only application/json is accepted for Content-Type in POST requests");
            return false;
        }

        return true;
    }

    /**
     * Check Authorization of request
     *
     * @param Micro $app
     * @return boolean
     */
    protected function authorize(Micro $app) :? object
    {
        $authorized = null;
        $config = $app->getService('jwt')["config"];

        $authorization = $app->request->getHeader('Authorization');
        $JWT_Token = self::getBearerToken($authorization);

        if (!is_null($JWT_Token)) {
            $signer = $config->signer();
            $key    = $config->verificationKey();
            $tokenParsed = $config->parser()->parse($JWT_Token);

            $constraints = [
                new IssuedBy($app->config->jwt->url),
                new PermittedFor($app->config->jwt->url),
                new SignedWith($signer, $key),
                new LooseValidAt(new SystemClock(new DateTimeZone($app->config->jwt->timezone)))
            ];

            try {
                $config->validator()->assert($tokenParsed, ...$constraints);
                $authorized = $tokenParsed;
            } catch (\Exception $e) {
                $authorized = null;
                if (in_array($app->config->mode, ['development' ,'testing'])) {
                    throw new \Exception($e->getMessage());
                }
            }
        }

        return $authorized;
    }

    public function call(Micro $app)
    {
        $this->app = $app;
        return true;
    }
    
    /**
     * Get Bearer token from authorization header
     *
     * @param string $authorization
     * @return string|null
     */
    public static function getBearerToken(?string $authorization) :? string
    {
        if (!empty($authorization)) {
            if (preg_match('/Bearer\s(\S+)/', $authorization, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * Get Route Name
     */
    public function getRouteName(Micro $app) :? string
    {
        $router = $app->router;
        if ($router === null || $router->getMatchedRoute() === null) {
            throw new Exception("Router is missing to get route name");
        }
        return $router->getMatchedRoute()->getName();
    }
}
