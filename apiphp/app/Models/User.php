<?php

namespace Meetingg\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

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
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
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
        $this->hasMany('id', 'Meetingg\Models\Contact', 'target_id', ['alias' => 'Contact']);
        $this->hasMany('id', 'Meetingg\Models\Contact', 'user_id', ['alias' => 'Contact']);
        $this->hasMany('id', 'Meetingg\Models\Discussionusers', 'user_id', ['alias' => 'Discussionusers']);
        $this->hasMany('id', 'Meetingg\Models\Group', 'user_id', ['alias' => 'Group']);
        $this->hasMany('id', 'Meetingg\Models\Invite', 'user_id', ['alias' => 'Invite']);
        $this->hasMany('id', 'Meetingg\Models\Meetingusers', 'user_id', ['alias' => 'Meetingusers']);
        $this->hasMany('id', 'Meetingg\Models\Message', 'user_id', ['alias' => 'Message']);
        $this->hasMany('id', 'Meetingg\Models\Notification', 'sender_id', ['alias' => 'Notification']);
        $this->hasMany('id', 'Meetingg\Models\Notification', 'user_id', ['alias' => 'Notification']);
        $this->belongsTo('invite_id', 'Meetingg\Models\Invite', 'id', ['alias' => 'Invite']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]|User|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return User|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null) : ? \Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
