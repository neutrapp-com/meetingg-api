<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\ProfileController;

$collection = new Collection();

$collection->setHandler(ProfileController::class, true);

$collection->setPrefix('/profile');

$collection
// getters
        ->get("/", "index")
        ->get("/data", "data")
// actions
        ->post("/avatar", "avatar")
        ->post("/update", "update")
        ;

return $collection;
