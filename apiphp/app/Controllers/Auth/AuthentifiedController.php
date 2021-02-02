<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Auth;

use Meetingg\Controllers\BaseController;
use Meetingg\Exception\PublicException;

/**
 *  Client Auth Controller
 */
class AuthentifiedController extends BaseController
{
    public bool $isLogged = false;
    public array $publicActions = [];

    public function onConstruct()
    { 
        if (!$this->isLogged) {
            throw new PublicException("You must be authentified to access to this resource");
        }
    }
     
}
