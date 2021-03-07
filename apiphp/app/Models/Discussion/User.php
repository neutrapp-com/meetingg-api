<?php

namespace Meetingg\Models\Discussion;

use Meetingg\Models\BaseModel;

class User extends BaseModel
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
    public $invite_id;

    /**
     *
     * @var string
     */
    public $discussion_id;

    /**
     *
     * @var string
     */
    public $permissions;

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
        $this->setSource("discussionusers");

        $this->belongsTo('discussion_id', 'Meetingg\Models\Discussion', 'id', ['alias' => 'Discussion']);
        $this->belongsTo('invite_id', 'Meetingg\Models\Invite', 'id', ['alias' => 'Invite']);
        $this->belongsTo('user_id', 'Meetingg\Models\User', 'id', ['alias' => 'User']);
    }
}
