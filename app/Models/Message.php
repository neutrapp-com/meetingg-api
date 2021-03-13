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

        $this->belongsTo('user_id', 'Meetingg\Models\User', 'id', ['alias' => 'User']);
        $this->belongsTo('discussion_id', 'Meetingg\Models\Discussion', 'id', ['alias' => 'Discussion']);
    }
}
