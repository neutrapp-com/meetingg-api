<?php
declare(strict_types=1);
namespace Meetingg\Controllers\User;

use Meetingg\Models\User;
use Meetingg\Controllers\Auth\AuthentifiedController;
use Meetingg\Controllers\IndexController;
use Meetingg\Exception\PublicException;
use Meetingg\Http\StatusCodes;

class ProfileController extends AuthentifiedController
{
    public function myprofile() :? array
    {
        $user = $this->getDI()->get('user');
        return [
            'data' => $user->getProfile()
        ];
    }

    public function userprofile(string $userId) :? array
    {
        $this->expectPermission('admin.user.profile');
        
        if (!User::validUUID($userId)) {
            throw new PublicException("Invalide id", StatusCodes::HTTP_BAD_REQUEST);
        }
            
        $user = User::findFirstById($userId);
        
        if (!$user) {
            throw new PublicException("User not found", StatusCodes::HTTP_NOT_FOUND);
        }
            
        return ['user'=> $user->getProfile()];
    }
}
