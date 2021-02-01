<?php
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */

use Meetingg\Exception\PublicException;
use Meetingg\Exception\Error\NotFound404;
use Meetingg\Controllers\IndexController;

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
 * Set Json Content Type
 */
$app->after(function () use ($app) {
    $content = $app->getReturnedValue();
    $content = is_array($content) || is_object($content) ? $content : ['data' => $content];
    
    $app->response->setContentType('application/json');
    $app->response->setJsonContent(array_merge_recursive(
        [
        'status' => $app->response->getStatusCode() ?: 200,
    ],
        $content
    ));
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
        $app->response->setJsonContent($_ENV['APP_MODE'] === 'production' && !is_subclass_of($e, PublicException::class, true) ? [
            'code'    => $codeError,
            'status'  => 'error',
            'message' => 'Something went wrong please contact support',
        ]
        :  [
            'code'    => $codeError,
            'status'  => 'error',
            'message' => $e->getMessage(),
        ])->send();
    }
);
