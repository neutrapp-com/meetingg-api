<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\User\ProfileController;

$collection = new Collection();

$collection->setHandler(ProfileController::class, true);

$collection->setPrefix('/profile');

$collection
// getters
    ->get("/", "index", "public")           // ok
    ->get("/me", "myProfile")               // ok
    ->get("/me/avatar", "getAvatar")        // ok
    ->get("/user/{id}", "userProfile")      // ok
// actions
    ->post("/update", "updateProfile")      // ok
    ->post("/avatar", "updateAvatar")       // #todo
    ;



return $collection;
