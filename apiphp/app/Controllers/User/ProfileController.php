<?php
declare(strict_types=1);
namespace Meetingg\Controllers\User;

use Meetingg\Controllers\Auth\AuthentifiedController;
use Meetingg\Models\User;

class ProfileController extends AuthentifiedController
{
    public function index()
    {
        return [
            '/profile/data',
            '/profile/update',
            '/profile/avatar',
        ];
    }
    public function data()
    {
        return [
                'user'=> [
                'username'=> 'test',
                'email'=> 'test@gmail.com',
            ]
        ];
    }
}
