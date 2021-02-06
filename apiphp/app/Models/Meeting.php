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
        $this->hasMany('id', 'Meetingg\Models\Discussion', 'meeting_id', ['alias' => 'Discussion']);
        $this->hasMany('id', 'Meetingg\Models\Invite', 'meeting_id', ['alias' => 'Invite']);
        $this->hasMany('id', 'Meetingg\Models\Meetingusers', 'meeting_id', ['alias' => 'Meetingusers']);
        $this->hasMany('id', 'Meetingg\Models\Notification', 'meeting_id', ['alias' => 'Notification']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Meeting[]|Meeting|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Meeting|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null) : ? \Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
