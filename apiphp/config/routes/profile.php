<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\User\ProfileController;

$collection = new Collection();

$collection->setHandler(ProfileController::class, true);

$collection->setPrefix('/profile');

$collection
// getters
    ->get("/", "index", "public")
    ->get("/me", "myProfile")
    ->get("/me/avatar", "getAvatar")
    ->get("/user/{id}", "userProfile")
// actions
    ->post("/avatar", "updateAvatar")
    ->post("/update", "updateProfile")
    ;



return $collection;
