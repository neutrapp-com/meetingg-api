<?php

namespace Meetingg\Models;

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
        $this->hasMany('target_id', 'Meetingg\Models\Group\Contacts', 'contact_id', ['alias' => 'GroupContacts']);
    }
}
