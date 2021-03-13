<?php

namespace Meetingg\Models;

class Meeting extends BaseModel
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
    public $title;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $video;

    /**
     *
     * @var string
     */
    public $audio;

    /**
     *
     * @var string
     */
    public $sharedscreen;

    /**
     *
     * @var string
     */
    public $locked;

    /**
     *
     * @var string
     */
    public $locked_at;

    /**
     *
     * @var string
     */
    public $start_at;

    /**
     *
     * @var string
     */
    public $end_at;

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
        $this->setSource("meeting");

        $this->hasMany('id', 'Meetingg\Models\Invite', 'meeting_id', ['alias' => 'Invite']);
        $this->hasMany('id', 'Meetingg\Models\Discussion', 'meeting_id', ['alias' => 'Discussion']);
        $this->hasMany('id', 'Meetingg\Models\Notification', 'meeting_id', ['alias' => 'Notification']);

        $this->hasMany('id', 'Meetingg\Models\Meeting\User', 'meeting_id', ['alias' => 'MeetingUsers']);
    }
}
