<?php
declare(strict_types=1);
namespace Meetingg\Controllers;

use Meetingg\Controllers\Auth\AuthentifiedController;

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
