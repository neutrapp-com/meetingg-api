<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Auth;

use DateTimeImmutable;
use Meetingg\Models\User;
use Meetingg\Exception\PublicException;
use Meetingg\Validators\LoginValidator;
use Meetingg\Controllers\BaseController;
use Meetingg\Validators\RegisterValidator;

/**
 *  Client Auth Controller
 */
class AuthController extends BaseController
{
    /**
     * Login Action
     *
     * @return array
     */
    public function login() :? array
    {
        $validator = new LoginValidator();
        $postData = $this->request->get();

        $errors = $validator->validate($postData);

        foreach ($errors as $error) {
            throw new PublicException($error->getMessage());
        }

        $user = User::findFirstByEmail($postData['email']);

        if (!$user) {
            // sleep(rand(1, 5)); // slow down if its using brutforce attack
            throw new PublicException("Invalid credentials.");
        }

        if (!$user->validatePassword($postData['password'])) {
            throw new PublicException("Invalid credentials.");
        }

        // return new JWT Token
        return [
            'token'=>
            $this->generateJWTSessionToken($user)
        ];
    }
    /**
     * Login Action
     *
     * @return array
     */
    public function register() :? array
    {
        $validator = new RegisterValidator();
        $postData = $this->request->get();

        $errors = $validator->validate($postData);

        foreach ($errors as $error) {
            throw new PublicException($error->getMessage());
        }

        $user = new User;
        $user->assign($postData, [
            'firstname',
            'lastname',
            'email',
            'password',
            'country',
            'city',
        ]);

        // save user
        $errors = $user->save();
        
        if (!$errors) {
            foreach ($user->getMessages() as $error) {
                throw new PublicException($error->getMessage());
            }
        }

        
        // return new JWT Token
        return [
            'message' => 'register successfully'
        ];
    }

    /**
     * Create New JWT Session Token
     *
     * @param User $user
     * @return string
     */
    public function generateJWTSessionToken(User $user) : string
    {
        $appConfig =  $this->getDI()->getConfig()->jwt;
        $config = $this->getDI()->getShared('jwt')["config"];
        $now   = new DateTimeImmutable();
        $expire = "+1 hours";

        return $config->builder()
            // Configures the issuer (iss claim)
            ->issuedBy($appConfig->url)
            // Configures the audience (aud claim)
            ->permittedFor($appConfig->url)
            // Configures the time that the token was issue (iat claim)
            ->issuedAt($now)
            // Configures the id (jti claim)
            ->identifiedBy(md5($user->id))
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify($expire))
            // Configures a new claim, called "uid"
            ->withClaim('uid', $user->id)
            // user info
            ->withHeader('fullname', $user->firstname . " " .$user->lastname)
            // Builds a new token
            ->getToken($config->signer(), $config->signingKey())
            ->toString();
    }
}
