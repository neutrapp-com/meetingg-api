<?php

namespace Meetingg\Middleware;

use Meetingg\Exception\PublicException;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Router;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function beforeExecuteRoute(Event $event, Micro $app)
    {
        $authorizeExceptions = [
            'index'
        ];
        if (!in_array($app->router->getMatchedRoute()->getName(), $authorizeExceptions)) {
            $result = $this->authorize($app);
            if (is_null($result)) {
                $app->response->setStatusCode(401,'Please authorize with valid API token!');
                throw new PublicException("Please authorize with valid API token");
                return false;
            }
        }

        if (in_array($app->request->getMethod(), ['POST', 'PUT']) AND $app->request->getHeader('Content-Type') != 'app/json') {
            $app->response->setStatusCode(400,'Only app/json is accepted for Content-Type in POST requests.');
            throw new PublicException("Only app/json is accepted for Content-Type in POST requests");
            return false;
        }

        return true;
    }

    private function authorize(Micro $app)
    {
        $app->token = null;
        $authorizationHeader = $app->request->getHeader('api-token');

        if (strlen($authorizationHeader) > 5) {  //check token validity and find from database what user has the token
            $app->token = $authorizationHeader;
            //$app->userid = ?;
        }

        return $app->token;
    }    

    public function call(Micro $app)
    {
        return true;
    }    
}