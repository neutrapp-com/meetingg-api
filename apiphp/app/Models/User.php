<?php

namespace Meetingg\Models;

use Meetingg\Models\Company\User as CompanyUser;
use Lcobucci\JWT\Token;
use Phalcon\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\InclusionIn;

class User extends BaseModel
{

    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var string
     */
    public $invite_id;

    /**
     *
     * @var string
     */
    public $firstname;

    /**
     *
     * @var string
     */
    public $lastname;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $avatar;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $fax;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $city;

    /**
     *
     * @var string
     */
    public $country;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var string
     */
    public $country_id;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     *
     * @var Token
     */
    public Token $sessionToken;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        
        $validator->add(
            'firstname',
            new StringLength([
                'max' => 50,
                'min' => 2,
                'messageMaximum' => 'The First Name is too long',
                'messageMinimum' => 'The First Name is too short',
            ])
        );

        $validator->add(
            'lastname',
            new StringLength([
                'max' => 50,
                'min' => 2,
                'messageMaximum' => 'The Last name is too long',
                'messageMinimum' => 'The Last name is too short',
            ])
        );

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );
        
        $validator->add(
            'email',
            new PresenceOf(
                [
                    'message' => 'The e-mail is required',
                ]
            )
        );

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'message' => 'The e-mail is not valid',
                ]
            )
        );


        $validator->add(
            'email',
            new Uniqueness(
                [
                    'model' => $this,
                    'message' => 'The :field already used',
                ]
            )
        );

        $validator->add(
            'city',
            new StringLength([
                'max' => 30,
                'min' => 2,
                'messageMaximum' => 'The :field is too long',
                'messageMinimum' => 'The :field is too short',
                'allowEmpty' =>true
            ])
        );

        $validator->add(
            'city',
            new Regex([
                'pattern' => "/^[a-zA-Z]+(?:[\s-][a-zA-Z]+)*$/",
                'message' => 'The :field is invalid',
                'allowEmpty' =>true,
            ])
        );

 
        $validator->add(
            'country_id',
            new InclusionIn([
                'domain' => Country::allKeys(),
                'message' => 'Invalid country',
            ])
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setDefaultSchema();
        $this->setSource("user");

        /**
         * User CompanyUser
         */
        $this->hasMany('id', 'Meetingg\Models\Company\User', 'user_id', ['alias' => 'CompanyUser']);
        
        /**
         * User Companies
         */
        $this->hasManyToMany(
            'id',
            CompanyUser::class,
            'user_id',
            'company_id',
            Company::class,
            'id',
            ['alias' => 'Companies']
        );

        /**
         * Keepsnapshots to detect if password changed,
         * to crypt it
         */
        $this->keepSnapshots(true);
    }

    /**
     * before Create
     */
    public function beforeCreate() : void
    {
        parent::beforeCreate();

        $this->username = self::getRandomUsername();
        $this->password = self::hashPassword($this->password);
    }
    
    /**
     * Before Save
     */
    public function beforeUpdate() : void
    {
        parent::beforeUpdate();

        if ($this->hasChanged('password')) {
            $this->password = self::hashPassword($this->password);
        }
    }

    /**
     * Validate Password
     */
    public function validatePassword(string $password) : bool
    {
        return $this
            ->getDI()
            ->getSecurity()
            ->checkHash($password, $this->password);
    }

    /**
     * Hash Password
     */
    public static function hashPassword($password) : string
    {
        $security = new Security();
        return $security
            ->hash($password);
    }

    /**
     * Get User Profile
     *
     * @param array $excludeFields
     * @param array $customIncludes
     * @param boolean $onlyCustom
     * @return array
     */
    public function getProfile(array $excludeFields = [], array $customIncludes = [], bool $onlyCustom = false) : array
    {
        $includeInputs = true === $onlyCustom ? $customIncludes : array_merge(
            ['id','firstname','lastname','city','country_id','email','avatar','fax', 'status','created_at','updated_at'],
            $customIncludes
        );

        $userData = [];
        foreach ($this->toArray() as $key => $val) {
            if (in_array($key, $includeInputs) && !in_array($key, $excludeFields)) {
                $userData[$key] = $val;
            }
        }
        
        return $userData;
    }

    /**
     * Generate random user
     *
     * @return string
     */
    public function getRandomUsername() : string
    {
        return preg_replace("/[^a-z0-9]/", '', strtolower("{$this->firstname}.{$this->lastname}").rand(1000, 9999));
    }

    /**
     * Get the value of sessionToken
     *
     * @return  Token|null
     */
    public function getSessionToken() :? Token
    {
        return $this->sessionToken;
    }

    /**
     * Set the value of sessionToken
     *
     * @param  Token  $sessionToken
     *
     * @return  self
     */
    public function setSessionToken(Token $sessionToken) :? self
    {
        $this->sessionToken = $sessionToken;

        return $this;
    }
}
