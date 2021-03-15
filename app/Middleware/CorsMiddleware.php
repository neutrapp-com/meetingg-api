<?php

namespace Meetingg\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Phalcon\Http\Request;

class CorsMiddleware extends BaseMiddleware
{
    private $allowedHosts = [];

    /**
     * @param Event $event
     * @param Micro $app
     */
    public function beforeExecuteRoute(Event $event, Micro $app)
    {
        $request = $app->request;
        $response = $app->response;
        $this->allowedHosts = explode(',', $app->config->headers->cors ?? '');

        if ($this->isCorsRequest($request)) {
            $response
                ->setHeader('Access-Control-Allow-Origin', $this->getOrigin($request))
                ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE')
                ->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization')
                ->setHeader('Access-Control-Allow-Credentials', 'true');
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isCorsRequest(Request $request)
    {
        return !empty($request->getHeader('Origin')) && $this->isAllowedHost($request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isAllowedHost(Request $request)
    {
        return $request->getHeader('Origin') === $this->getSchemeAndHttpHost($request) || in_array($request->getHeader('Origin'), $this->allowedHosts);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getSchemeAndHttpHost(Request $request)
    {
        return $request->getScheme() . '://' . $request->getHttpHost();
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getOrigin(Request $request)
    {
        return $request->getHeader('Origin') ? $request->getHeader('Origin') : '*';
    }
}
