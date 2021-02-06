<?php

namespace Meetingg\Models;

class Notification extends BaseModel
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
    public $sender_id;

    /**
     *
     * @var string
     */
    public $meeting_id;

    /**
     *
     * @var string
     */
    public $discussion_id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $content;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setDefaultSchema();
        $this->setSource("notification");
        $this->belongsTo('discussion_id', 'Meetingg\Models\Discussion', 'id', ['alias' => 'Discussion']);
        $this->belongsTo('meeting_id', 'Meetingg\Models\Meeting', 'id', ['alias' => 'Meeting']);
        $this->belongsTo('sender_id', 'Meetingg\Models\User', 'id', ['alias' => 'User']);
        $this->belongsTo('user_id', 'Meetingg\Models\User', 'id', ['alias' => 'User']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Notification[]|Notification|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Notification|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null) : ? \Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
