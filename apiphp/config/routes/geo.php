<?php

use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\Geo\GeoController;

$collection = new Collection();

$collection->setHandler(GeoController::class, true);

$collection->setPrefix('/geo');

$collection
    ->get("/countries", "countries", "public.cached")
    ;


return $collection;
