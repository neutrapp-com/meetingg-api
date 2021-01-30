<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\IndexController;

$collection = new Collection();

$collection->setHandler(IndexController::class, true);

$collection->setPrefix('/event');

$collection
// getters
    ->get("/data", "data")
// actions
    ->post("/new", "new")
    ->post("/move", "move")
    ->post("/update", "update")
    ->post("/delete", "delete")
    ;



return $collection;
