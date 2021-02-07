<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\User\ProfileController;

$collection = new Collection();

$collection->setHandler(ProfileController::class, true);

$collection->setPrefix('/profile');

$collection
// getters
        ->get("/", "index")
        ->get("/me", "myprofile")
        ->get("/user/{id}", "userprofile")
// actions
        ->post("/me/avatar", "avatar")
        ->post("/me/update", "update")
        ;



return $collection;
