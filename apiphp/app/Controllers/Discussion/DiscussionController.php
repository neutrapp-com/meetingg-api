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

/**
 *  Landing Index Controller
 */
class DiscussionController extends ApiModelController
{
    /** @var ROW_TITLE */
    const ROW_TITLE = 'Discussion';

    /** @var FOREIGN_KEYS */
    const FOREIGN_KEYS = [ 'user_id', 'discussion_id' ];

    /** @var PRIMARY_KEYS */
    const PRIMARY_KEYS = [ 'user_id', 'discussion_id' ];

    /** @var MODEL */
    const MODEL = DiscussionUser::class;

    const DELETE_ONE = false;

    /**
     * Foreign Keys
     *
     * @return array
     */
    protected function foreignkeys() : array
    {
        return [
            // 'meeting_id'=> $this->getUser()->id
        ];
    }

    /**
     * modal Find Params
     *
     * @return array
     */
    protected function modelFindParams() : array
    {
        return [
            'user_id = :userid:',
            'bind'=>  [
                'userid'=> $this->getUser()->id
            ]
        ];
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
            'row' =>
            parent::getOne([
                'id' => $targetId
            ])->Discussion->toArray()
        ];
    }

    /**
     * Get All User Rows
     *
     * @return array|null
     */
    public function getMyRows() :? array
    {
        return [
            'rows'=> parent::getMy()
        ];
    }
}
