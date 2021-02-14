<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\Meeting\MeetingController;

$collection = new Collection();

$collection->setHandler(MeetingController::class, true);

$collection->setPrefix('/meeting');

$collection
// getters
    ->get("/", "index", "public")               // ok
    ->get("/{id}", "getMeeting")                // ok  #toupgrade
    ->get("/upcoming", "upcoming")              // #todo
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
