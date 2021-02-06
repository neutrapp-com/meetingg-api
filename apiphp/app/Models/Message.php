<?php

namespace Meetingg\Models;

class Message extends BaseModel
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
    public $discussion_id;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var string
     */
    public $file;

    /**
     *
     * @var string
     */
    public $meta_file;

    /**
     *
     * @var string
     */
    public $starred;

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
        $this->setSource("message");
        $this->belongsTo('discussion_id', 'Meetingg\Models\Discussion', 'id', ['alias' => 'Discussion']);
        $this->belongsTo('user_id', 'Meetingg\Models\User', 'id', ['alias' => 'User']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Message[]|Message|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Message|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null) : ? \Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
