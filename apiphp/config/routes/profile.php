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
    ->get("/{id:[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}}", "userProfile")      // ok
// actions
    ->post("/update", "updateProfile")      // ok
    ->post("/avatar", "updateAvatar")       // #todo
    ;



return $collection;
