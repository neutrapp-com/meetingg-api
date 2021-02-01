<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\IndexController;

$collection = new Collection();

$collection->setHandler(IndexController::class, true);

$collection->setPrefix('/message');

$collection
// getters
    ->get("/{discussion}", "data")
// actions
    ->post("/{discussion}/send", "send")
    ->post("/{id}/update", "update")
    ->post("/{id}/delete", "delete")
    ;



return $collection;
