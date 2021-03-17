<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Auth;

use DateTimeImmutable;
use Meetingg\Models\User;
use Meetingg\Exception\PublicException;
use Meetingg\Validators\LoginValidator;
use Meetingg\Controllers\BaseController;
use Meetingg\Http\StatusCodes;
use Meetingg\Validators\RegisterValidator;
use Lcobucci\JWT\Token;
use Meetingg\Helpers\DateTimeFloatSerializer;

/**
 *  Client Auth Controller
 */
class AuthController extends BaseController
{
    /** @var REMEMBER_CLAIM */
    const REMEMBER_CLAIM = 'rmb';

    /** @var TOKEN_EXPIRE_TIME */
    const TOKEN_EXPIRE_TIME = "+24 hours";

    /** @var TOKEN_EXPIRE_TIME_REMEMBER */
    const TOKEN_EXPIRE_TIME_REMEMBER = "+15 days";

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

        // remember me
        $remember = isset($postData['remember']);

        // return new JWT Token
        $token = $this->generateJWTSessionToken($user, $remember ? self::TOKEN_EXPIRE_TIME : self::TOKEN_EXPIRE_TIME_REMEMBER, $remember ? [self::REMEMBER_CLAIM => 1] : [])->toString();
        return [
            'profile' => $user->getProfile(),
            'session' => ['token'=> $token]
        ];
    }
    

    /**
     * Session
     */
    public function session() :? array
    {
        if (false === $this->getDi()->has('user')) {
            throw new PublicException("Please authorize with valid API token.");
        }

        $user = $this->getDi()->get('user');

        // remember me
        $remember = $user->getSessionToken()->claims()->get(self::REMEMBER_CLAIM);

        if ($remember) {
            // expiration
            $expiration = $user->getSessionToken()->claims()->get('exp');
        
            if ($expiration > time() && $expiration <= time() + 60 * 60 * 2) {
                $user->setSessionToken($this->generateJWTSessionToken($user, self::TOKEN_EXPIRE_TIME_REMEMBER, [self::REMEMBER_CLAIM => 1]));
            }
        }

        return [
            'session' => array_merge($user->getProfile(), ['token'=> $user->getSessionToken()->toString()])
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
                throw new PublicException($error->getMessage(), StatusCodes::HTTP_UNAUTHORIZED);
            }
        }

        
        // return new JWT Token
        return [
            'message' => 'register successfully'
        ];
    }

    public function reset() :? array
    {
        return ['ok'];
    }

    /**
     * Create New JWT Session Token
     *
     * @param User $user
     * @return string
     */
    public function generateJWTSessionToken(User $user, string $expire = "+24 hours", array $claims = [], array $headers = []) :? Token
    {
        $appConfig =  $this->getDI()->getConfig()->jwt;
        $config = $this->getDI()->getShared('jwt')["config"];
        $now   = new DateTimeImmutable();

        $generatedToken =  $config
            ->builder(new DateTimeFloatSerializer(DateTimeFloatSerializer::default()))
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
            ->withClaim('uid', $user->id);

        foreach ($claims as $key => $val) {
            $generatedToken->withClaim($key, $val);
        }

        foreach ($headers as $key => $val) {
            $generatedToken->withHeader($key, $val);
        }
        
        return $generatedToken
            // Builds a new token
            ->getToken($config->signer(), $config->signingKey());
    }
}
