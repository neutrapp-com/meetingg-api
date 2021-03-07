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
    ->get("/{id:[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}}", "getevent")
// users
    ->get("/{id:[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}}/users", "getUsers")
    // actions
    ->post("/new", "newevent")
    ->post("/{id:[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}}/update", "updateEvent")
    ->post("/delete", "deleteEvent") // todo(skip for v2)
    ;


return $collection;
