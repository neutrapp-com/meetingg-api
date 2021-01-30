<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\Client\IndexController;

$collection = new Collection();

$collection->setHandler(IndexController::class, true);

$collection->setPrefix('/client');

$collection
        ->post("/profile", "profile")
        ;

return $collection;
