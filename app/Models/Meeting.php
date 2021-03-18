<?php

namespace Meetingg\Models;



use Meetingg\Models\Meeting\User as MeetingUser;

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

        $this->hasMany(
            'id',
            'Meetingg\Models\Invite',
            'meeting_id',
            [
                'reusable' => true,
                'alias' => 'Invite',
            ]
        );
        $this->hasMany(
            'id',
            'Meetingg\Models\Discussion',
            'meeting_id',
            [
                'reusable' => true,
                'alias' => 'Discussion',
            ]
        );
        $this->hasMany(
            'id',
            'Meetingg\Models\Notification',
            'meeting_id',
            [
                'reusable' => true,
                'alias' => 'Notification',
            ]
        );
        $this->hasMany(
            'id',
            MeetingUser::class,
            'meeting_id',
            [
                'reusable' => true,
                'alias' => 'MeetingUsers',
            ]
        );


        $this->hasManyToMany(
            'id',
            MeetingUser::class,
            'meeting_id',
            'user_id',
            User::class,
            'id',
            [
                'reusable' => true,
                'alias'=> 'Users',
                
            ]
        );
    }


    /**
     * Get Meeting Profile
     *
     * @return array
     */
    public function getProfile() : array
    {
        $profile = [];

        /**
         * Self toArray
         */
        foreach (['id', 'title' , 'description', 'audio','video','sharedscreen','start_at','end_at','created_at','updated_at'] as $key) {
            $profile[$key] = $this->$key;
        }
        $profile['timeleft'] =  strtotime($this->start_at) - time();

        $profile['users'] = [];
        
        /**
         * Users toArray
         */
        foreach ($this->Users ?: [] as $user) {
            array_push($profile['users'], $user->getProfile([], ['id','firstname','lastname','avatar'], true));
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

    
    public function beforeDelete(): void
    {
        parent::beforeDelete();

        $this->modelsManager->createQuery('DELETE FROM '. MeetingUser::class.' WHERE meeting_id = :id:')->execute(['id'=> $this->id]);
        $this->modelsManager->createQuery('DELETE FROM '. Notification::class.' WHERE meeting_id = :id:')->execute(['id'=> $this->id]);
    }
}
