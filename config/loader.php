<?php

/**
 * Registering an autoloader
 *
 * psr-4
 */
$loader = new \Phalcon\Loader();

$loader->registerNamespaces(
    [
       'Meetingg' => APP_PATH
    ]
)->register();
