<?php

namespace Meetingg\Models\Meeting;

use Meetingg\Models\BaseModel;

class User extends BaseModel
{

    /**
     *
     * @var string
     */
    public $meeting_id;

    /**
     *
     * @var string
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $invite_id;

    /**
     *
     * @var string
     */
    public $permissions;

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
        $this->setSource("meetingusers");

        $this->belongsTo('user_id', 'Meetingg\Models\User', 'id', ['alias' => 'User']);
        $this->belongsTo('invite_id', 'Meetingg\Models\Invite', 'id', ['alias' => 'Invite']);
        $this->belongsTo('meeting_id', 'Meetingg\Models\Meeting', 'id', ['alias' => 'Meeting']);
    }
}
