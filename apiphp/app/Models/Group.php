<?php

namespace Meetingg\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

use Meetingg\Models\Contact;
use Meetingg\Models\Group\Contact as GroupContact;

class Group extends BaseModel
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
    public $user_id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $count;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setDefaultSchema();
        $this->setSource("group");

        $this->belongsTo('user_id', 'Meetingg\Models\User', 'id', ['alias' => 'User']);

        $this->hasManyToMany(
            'id',
            GroupContact::class,
            'group_id',
            'contact_id',
            Contact::class,
            'id',
            ['alias' => 'Contacts']
        );
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation() : bool
    {
        $validator = new Validation();

        $validator->add(
            ['user_id','title'],
            new Uniqueness([
                'model' => $this,
                'message' => 'Group name already exists !',
            ])
        );

        return $this->validate($validator);
    }
}
