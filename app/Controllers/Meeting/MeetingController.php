<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Meeting;

use Phalcon\Mvc\Model\Transaction\Manager;
use Phalcon\Mvc\Model\Transaction\Failed;

use Meetingg\Models\Meeting;
use Meetingg\Http\StatusCodes;
use Meetingg\Models\Discussion;
use Meetingg\Library\Permissions;
use Meetingg\Exception\PublicException;
use Meetingg\Validators\MeetingValidator;
use Meetingg\Models\Meeting\User as MeetingUser;
use Meetingg\Controllers\Auth\ApiModelController;
use Meetingg\Models\Discussion\User as DiscussionUser;

/**
 *  Landing Index Controller
 */
class MeetingController extends ApiModelController
{
    /** @var ROW_TITLE */
    const ROW_TITLE = 'Meeting';

    /** @var FOREIGN_KEYS */
    const FOREIGN_KEYS = [ 'id' ];

    /** @var PRIMARY_KEYS */
    const PRIMARY_KEYS = [ 'id' ];

    /** @var MODEL */
    const MODEL = Meeting::class;

    const DELETE_ONE = false;

    /**
     * Foreign Keys
     *
     * @return array
     */
    protected function foreignkeys() : array
    {
        return [];
    }

    /**
     * modal Find Params
     *
     * @return array
     */
    protected function modelFindParams() : array
    {
        return [];
    }

    /**
     * New One Row
     *
     * @param string uuid $targetId
     * @return array|null
     */
    public function newMeeting() :? array
    {
        $validator = new MeetingValidator;
        $postData = $this->request->get();
        $errors = $validator->validate($postData);

        foreach ($errors as $error) {
            throw new PublicException($error->getMessage(), StatusCodes::HTTP_BAD_REQUEST);
        }

       
        /**
         * Convert Dates
         */
        $postData['start_at'] = Meeting::formatTime($postData['start_at']);
        $postData['end_at'] = Meeting::formatTime($postData['end_at']);
        
        /**
         * Transaction Insert Meeting & Users
         */
        
        $userId = $this->getUser()->id;
        $txManager   = new Manager();
        $transaction = $txManager->get();

        
        try {

            /**
             * Create Meeting
             */
            $meeting = new Meeting();
            $meeting->setTransaction($transaction);
            $meeting->assign($postData, ['title', 'description', 'start_at', 'end_at']);
            $meeting->setActive(true);

            if (false === $meeting->create()) {
                throw new \Exception("Meeting creating failed ! ");
            }

            /**
             * Create Discussion
             */
            $discussion = new Discussion();
            $discussion->meeting_id = $meeting->id;
            $discussion->title = $meeting->title;
            $discussion->setActive(true);

            if (false === $discussion->create()) {
                throw new \Exception("Cannot add members to meeting");
            }

            /**
             * Create Participants
             */
            
            $userPermission = Permissions::READ_MESSAGES | Permissions::SEND_MESSAGES | Permissions::DROP_MESSAGES;
            $adminPermission = Permissions::READ_MESSAGES | Permissions::SEND_MESSAGES | Permissions::DROP_MESSAGES | Permissions::ADMINISTRATOR;

            $participants = [];

            if (true === is_string($postData['participants'])) {
                $participant_list = explode(",", $postData['participants']);
            
                foreach ($participant_list as $user_invite_id) {
                    if (true === Meeting::validUUID($user_invite_id)) {
                        $participants[$user_invite_id] = $userPermission;
                    }
                }
            }

            $participants[$userId] = $adminPermission;

            foreach ($participants as $uid => $permissions) {
                $muser = new MeetingUser();
                $muser->setTransaction($transaction);
                $muser->meeting_id = $meeting->id;
                $muser->user_id = $uid;
                $muser->permissions = $permissions;
                $muser->setActive(true);

                if (false === $muser->create()) {
                    throw new \Exception("Cannot add members to meeting");
                }

                $duser = new DiscussionUser();
                $duser->setTransaction($transaction);
                $duser->discussion_id = $discussion->id;
                $duser->user_id = $uid;
                $duser->permissions = $permissions;
                $duser->setActive(true);

                if (false === $duser->create()) {
                    throw new \Exception("Cannot add members to meeting discussion");
                }
            }

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollback();
            throw new PublicException($e->getMessage(), StatusCodes::HTTP_BAD_GATEWAY);
        }

        return [
            'row' => $meeting->getProfile()
        ];
    }

    /**
     * Get One Row using target_id
     *
     * @param string uuid $targetId
     * @return array|null
     */
    public function getOneRow(string $targetId) :? array
    {
        return [
            'row' => $this->getMeeting($targetId)->getProfile()
        ];
    }

    /**
     * Get All User Rows
     *
     * @return array|null
     */
    public function getMyRows() :? array
    {
        $this->di->setShared('minimized_content', true);
        $rows = [];
        foreach ($this->getUser()->getMeetings() as $meeting) {
            array_push($rows, $meeting->getProfile());
        }
        return [
            'rows'=> $rows,
            'total'=> count($rows)
        ];
    }

    /**
     * Get All Meeting Members
     *
     * @return array|null
     */
    public function getMembmers(string $meetingId) :? array
    {
        $this->di->setShared('minimized_content', true);
        $rows = [];

        $meeting = $this->getMeeting($meetingId) ?? [];
        
        foreach ($meeting->Users as $member) {
            array_push($rows, $member->getProfile());
        }
        
        return [
            'rows'=> $rows,
            'total'=> count($rows)
        ];
    }

    /**
     * Delete one meeting
     *
     * @eturn array|null
     */
    public function deleteOneRow(string $meetingId) :? array
    {
        $meeting = $this->getMeeting($meetingId) ?? [];
        $user = $this->getUser();

        $permissions =  0x0;
        $users = $meeting->MeetingUsers ?? [];
        foreach ($users as $duser) {
            if ($duser->user_id == $user->id) {
                $permissions = $duser->permissions;
            }
        }

        if (!in_array(true, [
            ($permissions & Permissions::ADMINISTRATOR) == Permissions::ADMINISTRATOR,
        ])) {
            throw new PublicException("You dont have permission to delete meeting", StatusCodes::HTTP_FORBIDDEN);
        }
        
        try {
            $meeting->getDiscussion()->delete();

            $deleted = $meeting->delete();
        } catch (\Exception $e) {
            throw new PublicException("Cannot delete this meeting !", StatusCodes::HTTP_INTERNAL_SERVER_ERROR, [], [$e->getMessage() , $e->getTrace()]);
        }

        if (false === $deleted) {
            throw new PublicException("Cannot delete this meeting ", StatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }


        return [
            'delete'=> true
        ];
    }

    /**
     * Get Meeting By Id & User
     *
     * @param string $meetingId
     * @return object|null
     */
    protected function getMeeting(string $meetingId) :? object
    {
        $meeting = Meeting::userMeeting($meetingId, $this->getUser()->id);
        
        if (true === is_null($meeting)) {
            throw new PublicException("Row does not exist", StatusCodes::HTTP_NOT_FOUND);
        }

        return $meeting;
    }
}
