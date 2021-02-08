<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\IndexController;

$collection = new Collection();

$collection->setHandler(IndexController::class, true);

$collection->setPrefix('/event');

$collection
// getters
    ->get("/", "index", "public")
    ->get("/{id}", "data")
// actions
    ->post("/new", "index")
    ->post("/{id}/move", "move")
    ->post("/{id}/update", "update")
    ->post("/{id}/delete", "delete")
    ;


return $collection;
