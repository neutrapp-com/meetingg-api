<?php

namespace Meetingg\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Callback;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;

class Contact extends BaseModel
{

    /**
     *
     * @var string
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $target_id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var string
     */
    public $starred;

    /**
     *
     * @var string
     */
    public $blocked;

    /**
     *
     * @var string
     */
    public $blocked_at;

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
     * @var string
     */
    public $deleted_at;

    /**
     *
     * @var string
     */
    public $accepted_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setDefaultSchema();
        $this->setSource("contact");

        $this->belongsTo('user_id', 'Meetingg\Models\User', 'id', ['alias' => 'User']);
        $this->belongsTo('target_id', 'Meetingg\Models\User', 'id', ['alias' => 'User']);
        // $this->hasMany('target_id', 'Meetingg\Models\Group\Contacts', 'contact_id', ['alias' => 'GroupContacts']);
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'title',
            new PresenceOf(
                [
                    'message' => 'The title is required',
                ]
            )
        );

        $validator->add(
            'target_id',
            new Callback(
                [
                    'message' => 'User does not exist',
                    'callback' => function ($data) {
                        return true === is_string($data->target_id)
                            && false === is_null(User::findFirstById($data->target_id));
                    }
                ]
            )
        );

        $validator->add(
            ['user_id','target_id'],
            new Uniqueness(
                [
                    'model' => $this,
                    'message' => 'You are already in contact with this user',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Get Contact Profile
     *
     * @return array
     */
    public function getProfile() : array
    {
        $profile = [
            'user' => $this->User->getProfile([], ['firstname','lastname','avatar'], true)
        ];

        foreach (['target_id', 'title','stared','blocked','blocked_at','created_at','updated_at'] as $key) {
            $profile[$key] = $this->$key;
        }

        return $profile;
    }
}
