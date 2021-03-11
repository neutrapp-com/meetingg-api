<?php
use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\Contact\GroupController;

$collection = new Collection();

$collection->setHandler(GroupController::class, true);

$collection->setPrefix('/group');

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
