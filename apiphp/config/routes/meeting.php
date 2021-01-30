<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\IndexController;

$collection = new Collection();

$collection->setHandler(IndexController::class, true);

$collection->setPrefix('/meeting');

$collection
// getters
    ->get("/", "data")
    ->get("/data", "data")
    ->get("/upcoming", "upcoming")
    ->get("/recorded", "recorded")
// actions
    ->post("/new", "new")
    ->post("/end", "end")
    ->post("/join", "join")
    ->post("/start", "start")
    ->post("/leave", "leave")
    ->post("/invite", "invite")
    ;



return $collection;
