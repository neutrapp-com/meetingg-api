<?php

namespace Meetingg\Models;

use Meetingg\Models\User;
use Meetingg\Models\Discussion\User as DiscussionUser;

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

        $this->hasManyToMany(
            'id',
            DiscussionUser::class,
            'discussion_id',
            'user_id',
            User::class,
            'id',
            [
                'reusable' => true,
                'alias'=> 'Users'
            ]
        );
    }

    /**
     * Get Discussion Profile
     *
     * @return array
     */
    public function getProfile() : array
    {
        $profile = [
            'users' => []
        ];

        /**
         * Users toArray
         */
        foreach ($this->Users ?: [] as $user) {
            array_push($profile['users'], $user->getProfile([], ['id','firstname','lastname','avatar'], true));
        }
        
        /**
         * Self toArray
         */
        foreach (['id', 'title' ,'avatar' , 'color','created_at','updated_at'] as $key) {
            $profile[$key] = $this->$key;
        }

        return $profile;
    }

    /**
     * Get Discussion & User
     *
     * @param string $discussionId
     * @param string $userId
     * @return self|null
     */
    public static function userDiscussion(string $discussionId, string $userId) :? self
    {
        if (false === self::validUUID($discussionId)) {
            return null;
        }

        return self::query()
            ->join(DiscussionUser::class, "du.discussion_id = ".self::class.".id AND du.user_id = :user_id:", "du")
            ->andWhere(self::class.'.id = :target_id:')
            ->bind(
                [
                    'user_id' => $userId,
                    'target_id' => $discussionId
                ]
            )
            ->execute()
            ->getFirst();
    }
}
