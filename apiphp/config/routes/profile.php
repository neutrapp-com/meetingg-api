<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\User\ProfileController;

$collection = new Collection();

$collection->setHandler(ProfileController::class, true);

$collection->setPrefix('/profile');

$collection
// getters
        ->get("/data", "data")
// actions
        ->post("/avatar", "avatar")
        ->post("/update", "update")
        ;

return $collection;
