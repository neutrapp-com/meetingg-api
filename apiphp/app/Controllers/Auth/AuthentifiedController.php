<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Auth;

use Meetingg\Models\User;

use Meetingg\Http\StatusCodes;
use Meetingg\Exception\PublicException;
use Meetingg\Controllers\BaseController;

/**
 *  Client Auth Controller
 */
class AuthentifiedController extends BaseController
{
    protected User $user;


    /**
     * On Construct , Bind User into controller
     *
     * @return void
     */
    public function onConstruct() : void
    {
        if ($this->getDI()->has(self::AUTH_KEY)) {
            $this->user = $this->getDI()->get(self::AUTH_KEY);
        }
    }

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
        $userPermissions = $this->getDI()->get(self::AUTH_KEY)->permissions ?: '["test"]';
        $userPermissions = json_decode($userPermissions);
        
        if (!in_array($permissionId, $userPermissions)) {
            throw new PublicException("Forbidden actions", StatusCodes::HTTP_FORBIDDEN);
        }

        return true;
    }

    /**
     * Get the value of user
     */
    public function getUser() : User
    {
        return $this->user;
    }
}
