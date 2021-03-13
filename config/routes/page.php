<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\Page\IndexController;

$collection = new Collection();

$collection->setHandler(IndexController::class, true);

$collection->setPrefix('/page');

$collection
    ->get("/", "index", "public")
    ->post("/prices", "prices")
    ->post("/contact", "contact")
    ->post("/landing.json", "landingJson")
    ;


return $collection;
