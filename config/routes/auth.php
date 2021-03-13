<?php
use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\Auth\AuthController;

$collection = new Collection();

$collection->setHandler(AuthController::class, true);

$collection->setPrefix('/auth');

$collection
// getters
    ->get("/", "index", "public")
    ->get("/session", "session")
    // actions
    ->post("/login", "login", "public")
    ->post("/register", "register", "public")
    ->post("/reset", "reset", "public");



return $collection;
