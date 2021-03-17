<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\User\ProfileController;

$collection = new Collection();

$collection->setHandler(ProfileController::class, true);

$collection->setPrefix('/profile');

$collection
// getters
    ->get("/", "index", "public")           // ok
    ->get("/my", "myProfile")               // ok
    ->get("/my/avatar", "getAvatar")        // ok
    ->get("/{id:".UUID_REGEX."}", "userProfile")      // ok
// actions
    ->post("/search", "searchUser")
    ->post("/update", "updateProfile")      // ok
    ->post("/avatar", "updateAvatar")       // #todo_later
    ;



return $collection;
