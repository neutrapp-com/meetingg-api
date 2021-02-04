<?php

namespace Meetingg\Middleware;

use DateTimeZone;
use Lcobucci\Clock\SystemClock;
use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro\MiddlewareInterface;

use Meetingg\Exception\PublicException;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Meetingg\Http\StatusCodes;

class AuthMiddleware implements MiddlewareInterface
{
    public function beforeExecuteRoute(Event $event, Micro $app)
    {
        $authorizeExceptions = [
            'index'
        ];
        if (!in_array($app->router->getMatchedRoute()->getName(), $authorizeExceptions)) {
            $authorized = $this->authorize($app);
            if ($authorized !== false) {
                $app->response->setStatusCode(401, StatusCodes::HTTP_UNAUTHORIZED);
                throw new PublicException("Please authorize with valid API token");
                return false;
            }
        }

        if (in_array($app->request->getMethod(), ['POST', 'PUT']) and $app->request->getHeader('Content-Type') != 'app/json') {
            $app->response->setStatusCode(400, StatusCodes::HTTP_BAD_REQUEST);
            throw new PublicException("Only app/json is accepted for Content-Type in POST requests");
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
    private function authorize(Micro $app) : bool
    {
        $authorized = false;
        $config = $app->getService('jwt')["config"];

        $authorization = $app->request->getHeader('Authorization');
        $JWT_Token = self::getBearerToken($authorization);

        if (!is_null($JWT_Token)) {
            $signer = $config->signer();
            $key    = $config->verificationKey();
            $token = $config->parser()->parse($JWT_Token);

            $constraints = [
                new IssuedBy($app->config->jwt->url),
                new PermittedFor($app->config->jwt->url),
                new SignedWith($signer, $key),
                new LooseValidAt(new SystemClock(new DateTimeZone($app->config->jwt->timezone)))
            ];

            try {
                $config->validator()->assert($token, ...$constraints);
                $authorized = true;
            } catch (\Exception $e) {
                // list of constraints violation exceptions
                $authorized = false;
                if ($app->config->mode === "development") {
                    throw new PublicException($e->getMessage());
                }
            }
        }

        return $authorized;
    }

    public function call(Micro $app)
    {
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
}
