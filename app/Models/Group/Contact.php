<?php

namespace Meetingg\Models\Group;

use Meetingg\Models\BaseModel;

class Contact extends BaseModel
{

    /**
     *
     * @var string
     */
    public $group_id;

    /**
     *
     * @var string
     */
    public $contact_id;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $deleted_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setDefaultSchema();
        $this->setSource("groupcontacts");

        $this->belongsTo('group_id', 'Meetingg\Models\Group', 'id', ['alias' => 'Group']);
        $this->belongsTo('contact_id', 'Meetingg\Models\Contact', 'target_id', ['alias' => 'Contact']);
    }
}
