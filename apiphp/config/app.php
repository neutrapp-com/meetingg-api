<?php
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */

use Phalcon\Events\Manager;

use Meetingg\Http\StatusCodes;
use Meetingg\Library\Functions;
use Meetingg\Middleware\AuthMiddleware;
use Meetingg\Middleware\CacheMiddleware;
use Meetingg\Middleware\RateLimitMiddleware;
use Meetingg\Exception\PublicException;
use Meetingg\Exception\Error\NotFound404;

/**
 * Before Execute
 * - Throttler
 */
$eventsManager = new Manager();
// Rate Limiting
$eventsManager->attach('micro', new RateLimitMiddleware());
// Auth Middleware
$eventsManager->attach('micro', new AuthMiddleware());
// Cache Middleware
$eventsManager->attach('micro', new CacheMiddleware());

$app->setEventsManager($eventsManager);

/**
 * Convert application/json Data to Request
 */
$app->before(function () use ($app) {
    $contentType = $app->request->getHeader('Content-Type');
    $contentType = $_SERVER['CONTENT_TYPE'] ?? null;

    if (-1 !== strpos($contentType, '/json')) {
        $rawBody = $app->request->getJsonRawBody(true) ?? [];
        // inject params in the request

        foreach ($rawBody as $key => $value) {
            $_REQUEST[$key] = $value;
        }
    }
});

/**
 * Add your routes here
 */
foreach (glob(BASE_PATH . '/config/routes/{*,*/,*/*/}*.php', GLOB_BRACE) as $file) {
    $app->mount(require $file);
}

/**
 * Not found handler
 */
$app->notFound(function () use ($app) {
    throw new NotFound404();
});


/**
 * After Execute :
 * - Dynamic Json Content Type
 */
$app->after(function () use ($app) {
    $content = $app->getReturnedValue();

    if ($app->getDi()->has('minimized_content')) {
        $contentData = $content;
        
        $content = array_map(function ($arrayIndexed) {
            if (is_array($arrayIndexed) && Functions::indexedArray($arrayIndexed) === true && count($arrayIndexed) > 0) {
                $newArray = [
                    'columns'=> array_keys($arrayIndexed[array_key_first($arrayIndexed)]),
                    'rows'=> array_map(function ($row) {
                        return array_values($row);
                    }, $arrayIndexed)
                ];

                return $newArray;
            }

            return $arrayIndexed;
        }, $contentData);
    }

    $content = is_array($content) || is_object($content) ? $content : ['data' => $content];

    /**
     * Dynamic Response Content & Type
     */
    $app->response->setContentType('application/json');
    $app->response->setJsonContent(array_merge_recursive(
        [
        'status' => $app->response->getStatusCode() ?: "ok",
        ],
        $content
    ));

    /**
     * End & Send Response
     */
    $app->response->send();
});

// This is executed when the request has been served by the route handler and response has been returned
$app->finish(function () use ($app) {

    //Finally, send the prepared response, flush output buffers (HTTP header)
    !$app->response->isSent() && $app->response->send();

    //Stops the middleware execution avoiding than other middleware be executed
    $app->stop();
});

/**
 * Error Handling
 */
$app->error(
    function ($e) use ($app) {
        $codeError = $e->getCode() ?: 401;

        $app->response->setContentType('application/json');
        $app->response->setJsonContent(
            array_merge(
                $_ENV['APP_ENV'] === 'production' && (!is_subclass_of($e, PublicException::class, true) && get_class($e) !== PublicException::class) ? [
                    'code'    => $codeError,
                    'status'  => 'error',
                    'message' => 'Something went wrong please contact support',
                ]
                :  [
                    'code'    => $codeError,
                    'status'  => 'error',
                    'message' => $e->getMessage(),
                    'trace'   => $e->getTrace()
                ],
                (property_exists(get_class($e), 'data') ? $e->getData() : [])
            )
        );
        
        /**
         * Aditions Exception Headers
         */
        if (property_exists(get_class($e), 'headers')) {
            foreach ($e->getHeaders() as $hname => $hvalue) {
                $app->response->setHeader($hname, $hvalue);
            }
        }
        
        /**
         * Dynamic response messages
         */
        $statusCode = StatusCodes::parseCode($app->response->getStatusCode() ?: $codeError);
        $app->response->setStatusCode($statusCode, StatusCodes::getMessageForCode($statusCode));
    
        $app->response->send();
    }
);
