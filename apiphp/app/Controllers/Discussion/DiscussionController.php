<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Discussion;

use Phalcon\Mvc\Model\Transaction\Manager;
use Phalcon\Mvc\Model\Transaction\Failed;

use Meetingg\Http\StatusCodes;
use Meetingg\Models\Discussion;
use Meetingg\Exception\PublicException;
use Meetingg\Controllers\Auth\ApiModelController;
use Meetingg\Library\Permissions;
use Meetingg\Models\Discussion\User as DiscussionUser;
use Meetingg\Models\Message;

/**
 *  Landing Index Controller
 */
class DiscussionController extends ApiModelController
{
    /** @var ROW_TITLE */
    const ROW_TITLE = 'Discussion';

    /** @var FOREIGN_KEYS */
    const FOREIGN_KEYS = [ 'id' ];

    /** @var PRIMARY_KEYS */
    const PRIMARY_KEYS = [ 'id' ];

    /** @var MODEL */
    const MODEL = Discussion::class;

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
    public function newDiscussion(string $targetId) :? array
    {
        $this->validUUIDOrThrowException($targetId);
        
        $userId = $this->getUser()->id;
        $dclass = Discussion::class;
        $discussion = Discussion::query()
            ->join(DiscussionUser::class, "du.discussion_id = $dclass.id AND du.user_id = :user_id:", "du")
            ->join(DiscussionUser::class, "dtu.discussion_id = $dclass.id AND dtu.user_id = :target_id:", "dtu")
            ->join(DiscussionUser::class, "allu.discussion_id = $dclass.id", "allu")
            ->bind(
                [
                    'user_id' => $targetId,
                    'target_id' => $targetId,
                ]
            )
            ->groupby("$dclass.id")
            ->having("count($dclass.id) = 2")
            ->execute();
        
        $discussion = $discussion->getFirst();

        if (true === is_null($discussion)) {
            $txManager   = new Manager();
            $transaction = $txManager->get();
            
            try {
                $discussion = new Discussion();
                $discussion->setTransaction($transaction);
                $discussion->setActive(true);

                if (false === $discussion->create()) {
                    throw new \Exception("Discussion creating failed ! ");
                }

                foreach ([$userId, $targetId] as $uid) {
                    $duser = new DiscussionUser();
                    $duser->setTransaction($transaction);
                    $duser->user_id = $uid;
                    $duser->discussion_id = $discussion->id;
                    $duser->permissions = Permissions::READ_MESSAGES | Permissions::SEND_MESSAGES | Permissions::DROP_MESSAGES | Permissions::ADMINISTRATOR;
                    $duser->setActive(true);

                    if (false === $duser->create()) {
                        throw new \Exception("Cannot add members to discussion");
                    }
                }

                $transaction->commit();
            } catch (\Throwable $e) {
                $transaction->rollback();
                throw new PublicException($e->getMessage(), StatusCodes::HTTP_BAD_GATEWAY);
            }
        }

        return [
            'row' => $discussion->getProfile()
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
            'row' => $this->getDiscussion($targetId)->getProfile()
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
        foreach ($this->getUser()->getDiscussions() as $discussion) {
            array_push($rows, $discussion->getProfile());
        }
        return [
            'rows'=> $rows
        ];
    }

    /**
     * Get Messages by discussion
     *
     * @param string $discussionId
     * @return array|null
     */
    public function getMessages(string $targetId) :? array
    {
        $discussion = $this->getDiscussion($targetId);

        /**
         * Fetch Messages
         */
        $max_date = Discussion::getTime();

        $messages = [];
        foreach (Message::find([
            'discussion_id = :discussion_id: AND created_at > :max_date:',
            'bind' => [
                'discussion_id' => $discussion->id,
                'max_date'=> $max_date
            ],
            'order'=>'created_at DESC',
            'limit'=> 20
        ]) as $message) {
            $messages[] = $message->getArray(['user_id','content','file','meta_file','starred','status','created_at']);
        }

        return [
            'rows' => $messages
        ];
    }

    /**
     * Get Discussion By Id & User
     *
     * @param string $discussionId
     * @return object|null
     */
    private function getDiscussion(string $discussionId) :? object
    {
        $discussion = Discussion::userDiscussion($discussionId, $this->getUser()->id);
        
        if (true === is_null($discussion)) {
            throw new PublicException("Row does not exist", StatusCodes::HTTP_NOT_FOUND);
        }

        return $discussion;
    }
}
