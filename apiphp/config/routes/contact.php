<?php
use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\Contact\ContactController;

$collection = new Collection();

$collection->setHandler(ContactController::class, true);

$collection->setPrefix('/contact');

$collection
// getters
    ->get("/", "index", "public")

    ->get("/my", "getMyRows")
    ->get("/{id:".UUID_REGEX."}", "getOneRow")
// actions
    ->post("/new", "newOneRow")
    ->post("/{id:".UUID_REGEX."}/update", "updateOneRow")
    ->post("/{id:".UUID_REGEX."}/delete", "deleteOneRow")
    ;


return $collection;
