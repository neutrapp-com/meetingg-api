<?php

namespace Meetingg\Models;

class Discussion extends BaseModel
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
    public $meeting_id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $avatar;

    /**
     *
     * @var string
     */
    public $color;

    /**
     *
     * @var string
     */
    public $favorite;

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
        $this->setSource("discussion");

        $this->belongsTo('meeting_id', 'Meetingg\Models\Meeting', 'id', ['alias' => 'Meeting']);
        $this->hasMany('id', 'Meetingg\Models\Message', 'discussion_id', ['alias' => 'Message']);

        $this->hasMany('id', 'Meetingg\Models\Notification', 'discussion_id', ['alias' => 'Notification']);
        $this->hasMany('id', 'Meetingg\Models\Discussion\User', 'discussion_id', ['alias' => 'DiscussionUsers']);
    }
}
