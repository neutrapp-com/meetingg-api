<?php
use Phalcon\Mvc\Micro\Collection;
use Meetingg\Controllers\Discussion\MessageController;

$collection = new Collection();

$collection->setHandler(MessageController::class, true);

$collection->setPrefix('/message');

$collection
// getters
    ->get("/", "index", "public")

    ->get("/{discussion:".UUID_REGEX."}", "getMyRows")
    ->get("/{discussion:".UUID_REGEX."}/{message:".UUID_REGEX."}", "getOneRow")
// actions
    ->post("/{discussion:".UUID_REGEX."}", "sendMesage")
    ->patch("/{discussion:".UUID_REGEX."}/{message:".UUID_REGEX."}", "updateMessage")
    ->delete("/{discussion:".UUID_REGEX."}/{message:".UUID_REGEX."}", "deleteMessage")
    ;


return $collection;
