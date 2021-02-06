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
        $this->hasMany('target_id', 'Meetingg\Models\Groupcontacts', 'contact_id', ['alias' => 'Groupcontacts']);
        $this->belongsTo('target_id', 'Meetingg\Models\User', 'id', ['alias' => 'User']);
        $this->belongsTo('user_id', 'Meetingg\Models\User', 'id', ['alias' => 'User']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Contact[]|Contact|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Contact|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null) : ? \Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

}
