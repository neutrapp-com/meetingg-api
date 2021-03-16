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

        $this->hasMany('id', 'Meetingg\Models\Invite', 'meeting_id', ['alias' => 'Invite']);
        $this->hasMany('id', 'Meetingg\Models\Discussion', 'meeting_id', ['alias' => 'Discussion']);
        $this->hasMany('id', 'Meetingg\Models\Notification', 'meeting_id', ['alias' => 'Notification']);

        $this->hasMany('id', 'Meetingg\Models\Meeting\User', 'meeting_id', ['alias' => 'MeetingUsers']);
    }


    /**
     * Get Meeting Profile
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
     * Get Meeting & User
     *
     * @param string $meetingId
     * @param string $userId
     * @return self|null
     */
    public static function userMeeting(string $meetingId, string $userId) :? self
    {
        if (false === self::validUUID($meetingId)) {
            return null;
        }

        return self::query()
            ->join(MeetingUser::class, "du.meeting_id = ".self::class.".id AND du.user_id = :user_id:", "du")
            ->andWhere(self::class.'.id = :meeting_id:')
            ->bind(
                [
                    'user_id' => $userId,
                    'meeting_id' => $meetingId
                ]
            )
            ->execute()
            ->getFirst();
    }

}
