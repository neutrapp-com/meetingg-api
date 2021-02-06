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
        $this->hasMany('id', 'Meetingg\Models\Discussionusers', 'invite_id', ['alias' => 'Discussionusers']);
        $this->hasMany('id', 'Meetingg\Models\Meetingusers', 'invite_id', ['alias' => 'Meetingusers']);
        $this->hasMany('id', 'Meetingg\Models\User', 'invite_id', ['alias' => 'User']);
        $this->belongsTo('meeting_id', 'Meetingg\Models\Meeting', 'id', ['alias' => 'Meeting']);
        $this->belongsTo('user_id', 'Meetingg\Models\User', 'id', ['alias' => 'User']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Invite[]|Invite|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Invite|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null) : ? \Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
