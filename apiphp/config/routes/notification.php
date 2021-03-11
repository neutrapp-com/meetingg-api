<?php
use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\NotificationController;

$collection = new Collection();

$collection->setHandler(NotificationController::class, true);

$collection->setPrefix('/notification');

$collection
// getters
    ->get("/", "index", "public")

    ->get("/my", "getMyRows")
    ->get("/{id:[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}}", "getOneRow")
// actions
    ->post("/{id:[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}}/update", "updateOneRow")
    ;


return $collection;
