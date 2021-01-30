<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\IndexController;

$collection = new Collection();

$collection->setHandler(IndexController::class, true);

$collection->setPrefix('/message');

$collection
// getters
    ->get("/data", "data")
// actions
    ->post("/send", "send")
    ->post("/update", "update")
    ->post("/delete", "delete")
    ;



return $collection;
