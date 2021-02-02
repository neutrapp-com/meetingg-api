<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\IndexController;

$collection = new Collection();

$collection->setHandler(IndexController::class, true);

$collection->setPrefix('');

$collection
    ->get("/", "index" , "index")
    ;


return $collection;
