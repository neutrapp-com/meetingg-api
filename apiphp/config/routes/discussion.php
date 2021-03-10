<?php
use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\Discussion\DiscussionController;

$collection = new Collection();

$collection->setHandler(DiscussionController::class, true);

$collection->setPrefix('/discussion');

$collection
// getters
    ->get("/", "index", "public")

    ->get("/my", "getMy")
    ->get("/{id:[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}}", "getOneRow")
    ;


return $collection;
