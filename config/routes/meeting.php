<?php
use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\Meeting\MeetingController;

$collection = new Collection();

$collection->setHandler(MeetingController::class, true);

$collection->setPrefix('/meeting');

$collection
// getters
    ->get("/", "index", "public")

    ->get("/my", "getMyRows")
    ->get("/{id:".UUID_REGEX."}", "getOneRow")
    ->get("/{id:".UUID_REGEX."}/members", "getMembmers")
// actions
    ->post("/new", "newMeeting")
    ->post("/{id:".UUID_REGEX."}/delete", "deleteOneRow")
    ;


return $collection;
