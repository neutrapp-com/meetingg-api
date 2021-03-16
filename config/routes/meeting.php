<?php
use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\Discussion\DiscussionController;

$collection = new Collection();

$collection->setHandler(DiscussionController::class, true);

$collection->setPrefix('/discussion');

$collection
// getters
    ->get("/", "index", "public")

    ->get("/my", "getMyRows")
    ->get("/{id:".UUID_REGEX."}", "getOneRow")
    ->get("/{id:".UUID_REGEX."}/messages", "getMessages")
// actions
    ->post("/{id:".UUID_REGEX."}/new", "newMeeting")
    ;


return $collection;
