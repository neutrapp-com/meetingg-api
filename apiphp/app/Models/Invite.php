<?php

namespace Meetingg\Models;

class Invite extends BaseModel
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
    public $meeting_id;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $limit;

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
    public $expirated_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setDefaultSchema();
        $this->setSource("invite");

        $this->hasMany('id', 'Meetingg\Models\User', 'invite_id', ['alias' => 'User']);
        $this->belongsTo('user_id', 'Meetingg\Models\User', 'id', ['alias' => 'User']);

        $this->belongsTo('meeting_id', 'Meetingg\Models\Meeting', 'id', ['alias' => 'Meeting']);

        $this->hasMany('id', 'Meetingg\Models\Meeting\User', 'invite_id', ['alias' => 'MeetingUsers']);
        $this->hasMany('id', 'Meetingg\Models\Discussion\User', 'invite_id', ['alias' => 'DiscussionUsers']);
    }
}
