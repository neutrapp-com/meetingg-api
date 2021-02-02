<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\ProfileController;

$collection = new Collection();

$collection->setHandler(ProfileController::class, true);

$collection->setPrefix('/profile');

$collection
// getters
        ->get("/", "index")
        ->get("/me", "index")
        ->get("/{id}", "data")
// actions
        ->post("/me/avatar", "avatar")
        ->post("/me/update", "update")
        ;



return $collection;
