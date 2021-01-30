<?php
use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\Auth\AuthController;

$collection = new Collection();

$collection->setHandler(AuthController::class, true);

$collection->setPrefix('/auth');

$collection
// getters
        ->get("/session", "session")
// actions
        ->post("/login", "login")
        ->post("/register", "register")
        ->post("/forgetpassword", "forgetpassword");

return $collection;
