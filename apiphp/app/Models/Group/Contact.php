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
