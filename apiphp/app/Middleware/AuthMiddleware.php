<?php

namespace Meetingg\Middleware;

use DateTimeZone;
use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;

use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

use Meetingg\Models\User;
use Meetingg\Http\StatusCodes;
use Meetingg\Exception\PublicException;

class AuthMiddleware extends BaseMiddleware
{
    protected Micro $app;

    public function beforeExecuteRoute(Event $event, Micro $app)
    {
        if (false === $this->matchRoute($app, 'public')) {
            $authorization = $this->authorize($app);
            
            if (false !== is_null($authorization)) {
                $app->response->setStatusCode(401, StatusCodes::HTTP_UNAUTHORIZED);
                throw new PublicException("Please authorize with valid API token");
                return false;
            }

            $app->getDI()->setShared('user', function () use ($authorization) {
                $user = User::findFirstById($authorization->claims()->get('uid'));
                $user->setSessionToken($authorization);

                return $user;
            });
        }

        if (in_array($app->request->getMethod(), ['POST', 'PUT']) and false === strpos($app->request->getHeader('Content-Type'), '/json')) {
            $app->response->setStatusCode(400, StatusCodes::HTTP_BAD_REQUEST);
            throw new PublicException("Only application/json is accepted for Content-Type in POST requests {$app->request->getHeader('Content-Type')}");
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
