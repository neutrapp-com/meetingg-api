<?php
declare(strict_types=1);
namespace Meetingg\Controllers\User;

use Phalcon\Http\Response;

use Meetingg\Models\User;
use Meetingg\Http\StatusCodes;
use Meetingg\Controllers\Auth\AuthentifiedController;
use Meetingg\Controllers\IndexController;
use Meetingg\Exception\PublicException;
use Meetingg\Validators\ProfileValidator;

class ProfileController extends AuthentifiedController
{

    /**
     * Slef User Profile
     *
     * @return array|null
     */
    public function myProfile() :? array
    {
        return [
            'data' => $this->user->getProfile()
        ];
    }

    /**
     * Self User avatar
     *
     * @return Response
     */
    public function getAvatar() : Response
    {
        $this->response->redirect($this->getUser()->avatar);
        return $this->response->send();
    }

    /**
     * Self Update Profile
     *
     * @return array
     */
    public function updateProfile() : array
    {
        $postData = $this->request->get();

        $user = $this->getUser();

        $items = array_filter([
            'firstname',
            'lastname',
            'email',
            'password',
            'country',
            'city',
        ], function ($item) use ($postData) {
            return !empty($postData[$item]);
        });

        $user->assign($postData, $items);

        // save user
        $errors = $user->update();
        
        if (!$errors) {
            foreach ($user->getMessages() as $error) {
                throw new PublicException($error->getMessage());
            }
        }

        
        // return new JWT Token
        return [
            'message' => 'profile updated successfully'
        ];
    }


    /**
     * Get any user profile, permission requried
     *
     * @param string $userId
     * @return array|null
     */
    public function userProfile(string $userId) :? array
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
