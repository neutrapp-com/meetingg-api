<?php
use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\Client\AuthController;

$collection = new Collection();

$collection->setHandler(AuthController::class, true);

$collection->setPrefix('/client/auth');

$collection
        ->post("/login", "login")
        ->post("/register", "register")
        ->post("/forgetpassword", "forgetpassword");

return $collection;
