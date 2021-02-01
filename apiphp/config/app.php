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
$app->get('/', [
    new IndexController(),
    "index"
]);

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
    $app->response->setContentType('application/json');
    $app->response->setJsonContent($app->response->getContent())->send();
});

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
