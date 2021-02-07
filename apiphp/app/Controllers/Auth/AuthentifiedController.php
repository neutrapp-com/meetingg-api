<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Auth;

use Meetingg\Http\StatusCodes;
use Meetingg\Exception\PublicException;
use Meetingg\Controllers\BaseController;

/**
 *  Client Auth Controller
 */
class AuthentifiedController extends BaseController
{
    /**
     * Permissions expecting to do an action
     *
     * @throws PublicException
     *
     * @param string $permissionId
     * @return boolean|null
     */
    protected function expectPermission(string $permissionId) :? bool
    {
        $userPermissions = $this->getDI()->get('user')->permissions ?: '["test"]';
        $userPermissions = json_decode($userPermissions);
        
        if (!in_array($permissionId, $userPermissions)) {
            throw new PublicException("Forbidden actions", StatusCodes::HTTP_FORBIDDEN);
        }

        return true;
    }
}
