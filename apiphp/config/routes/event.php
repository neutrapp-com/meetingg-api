<?php
use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\Event\EventController;

$collection = new Collection();

$collection->setHandler(EventController::class, true);

$collection->setPrefix('/event');

$collection
// getters
    ->get("/", "index", "public")

    ->get("/my", "getMyEvents")
    ->get("/{id:".UUID_REGEX."}", "getevent")
// users
    ->get("/{id:".UUID_REGEX."}/users", "getUsers")
    // actions
    ->post("/new", "newevent")
    ->post("/{id:".UUID_REGEX."}/update", "updateEvent")
    ->post("/delete", "deleteEvent") // todo(skip for v2)
    ;


return $collection;
