<?php
use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\Group\GroupController;

$collection = new Collection();

$collection->setHandler(GroupController::class, true);

$collection->setPrefix('/group');

$collection
// getters
    ->get("/", "index", "public")

    ->get("/my", "getMy")
    ->get("/{id:[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}}", "getOne")
// actions
    ->post("/new", "newOne")
    ->post("/{id:[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}}/update", "updateOne")
    ->post("/delete", "deleteOne") // todo(skip for v2)
    ;


return $collection;
