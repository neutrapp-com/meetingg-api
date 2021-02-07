<?php
declare(strict_types=1);

use Meetingg\Exception\PublicException;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
 
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


try {
    
    /**
     * Handle the request
     */
    $app->handle($_SERVER['REQUEST_URI']);
} catch (\Exception $e) {
    if (!($e instanceof PublicException)) {
        // log errors
        //print_r($e);
    }
}
