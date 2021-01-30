<?php
declare(strict_types=1);

use Exception;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Meetingg\Exceptions\PublicException;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {
    /**
     * The FactoryDefault Dependency Injector automatically registers the services that
     * provide a full stack framework. These default services can be overidden with custom ones.
     */
    $di = new FactoryDefault();

    /**
     * Include Services
     */
    include BASE_PATH . '/config/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include BASE_PATH . '/config/loader.php';

    /**
     * Starting the application
     * Assign service locator to the application
     */
    $app = new Micro($di);

    /**
     * Include Application
     */
    include BASE_PATH . '/config/app.php';

    /**
     * Handle the request
     */
    $app->handle($_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    /**
     * HTTP Code Error
     */
    $codeError = $e->getCode() ?: 401;

    /**
     * Header Content Type 
     * Error Message 
     */
    header('Content-Type: application/json');
    http_response_code($codeError);
    
    echo json_encode(
        $_ENV['APP_MODE'] === 'production' && !is_subclass_of($e , PublicException::class, true) ? [
            'code'    => $codeError,
            'status'  => 'error',
            'message' => 'Something went wrong please contact support',
        ]
        :  [
            'code'    => $codeError,
            'status'  => 'error',
            'message' => $e->getMessage(),
        ]
    );
}
