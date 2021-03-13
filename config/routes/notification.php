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
    ->get("/{id:".UUID_REGEX."}", "getOneRow")
// actions
    ->post("/{id:".UUID_REGEX."}/update", "updateOneRow")
    ;


return $collection;
