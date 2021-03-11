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
    ->get("/{id:[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}}", "getOneRow")
// actions
    ->post("/new", "newOneRow")
    ->post("/{id:[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}}/update", "updateOneRow")
    ->post("/{id:[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}}/delete", "deleteOneRow")
    ;


return $collection;
