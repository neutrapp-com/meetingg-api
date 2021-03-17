<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Meeting;

use Phalcon\Mvc\Model\Transaction\Manager;
use Phalcon\Mvc\Model\Transaction\Failed;

use Meetingg\Http\StatusCodes;
use Meetingg\Models\Meeting;
use Meetingg\Exception\PublicException;
use Meetingg\Controllers\Auth\ApiModelController;
use Meetingg\Library\Permissions;
use Meetingg\Models\Meeting\User as MeetingUser;
use Meetingg\Models\Message;
use Meetingg\Validators\MeetingValidator;

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
            throw new PublicException($error->getMessage());
        }


        $userId = $this->getUser()->id;

        $txManager   = new Manager();
        $transaction = $txManager->get();
        
        try {
            $meeting = new Meeting();
            $meeting->setTransaction($transaction);
            $meeting->assign($postData, ['title', 'description', 'start_at', 'end_at']);
            $meeting->setActive(true);

            if (false === $meeting->create()) {
                throw new \Exception("Meeting creating failed ! ");
            }

            foreach ([$userId] as $uid) {
                $duser = new MeetingUser();
                $duser->setTransaction($transaction);
                $duser->user_id = $uid;
                $duser->meeting_id = $meeting->id;
                $duser->permissions = Permissions::READ_MESSAGES | Permissions::SEND_MESSAGES | Permissions::DROP_MESSAGES | Permissions::ADMINISTRATOR;
                $duser->setActive(true);

                if (false === $duser->create()) {
                    throw new \Exception("Cannot add members to meeting");
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
        $rows = [];
        foreach ($this->getUser()->getMeetings() as $meeting) {
            array_push($rows, $meeting->getProfile());
        }
        return [
            'rows'=> $rows
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
