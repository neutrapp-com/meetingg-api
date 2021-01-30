<?php

/**
 * Registering an autoloader
 */
$loader = new \Phalcon\Loader();

$loader->registerNamespaces(
    [
       'Meetingg' => APP_PATH
    ]
)->register();
