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
    ->get("/{id:[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}}", "getOneRow")
    ->get("/{id:[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}}/messages", "getMessages")
// actions
    ->post("/{id:[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}}/new", "newDiscussion")
    ;


return $collection;
