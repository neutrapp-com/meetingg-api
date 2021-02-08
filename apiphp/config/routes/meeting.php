<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\IndexController;

$collection = new Collection();

$collection->setHandler(IndexController::class, true);

$collection->setPrefix('/meeting');

$collection
// getters
    ->get("/", "index", "public")
    ->get("/{id}", "data")
    ->get("/upcoming", "upcoming")
    ->get("/recorded", "recorded")
// actions
    ->post("/new", "new")
    ->post("/{id}/end", "end")
    ->post("/{id}/join", "join")
    ->post("/{id}/start", "start")
    ->post("/{id}/leave", "leave")
    ->post("/{id}/invite", "invite")
    ;


return $collection;
