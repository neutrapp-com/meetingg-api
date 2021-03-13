<?php
declare(strict_types=1);

namespace Meetingg\Exception\Error;

use Meetingg\Exception\PublicException;

class NotFound404 extends PublicException
{
    protected $message = "Route does not exist";
}
