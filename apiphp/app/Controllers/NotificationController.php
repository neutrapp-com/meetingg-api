<?php
declare(strict_types=1);

namespace Meetingg\Controllers;

use Meetingg\Models\Notification;
use Meetingg\Controllers\Auth\ApiModelController;

/**
 *  Landing Index Controller
 */
class NotificationController extends ApiModelController
{
    /** @var ROW_TITLE */
    const ROW_TITLE = 'notification';

    /** @var DATA_ASSIGN_UPDATE */
    const DATA_ASSIGN_UPDATE = [ 'status' ];

    /** @var FOREIGN_KEYS */
    const FOREIGN_KEYS = [ 'user_id', 'sender_id', 'meeting_id', 'discussion_id' ];

    /** @var PRIMARY_KEYS */
    const PRIMARY_KEYS = [ 'id' ];

    /** @var INSERT_ROW_ACTIVE */
    const INSERT_ROW_ACTIVE = false;

    /** @var MODEL */
    const MODEL = Notification::class;

    /**
     * Foreign Keys
     *
     * @return array
     */
    protected function foreignkeys() : array
    {
        return [
            'user_id'=> $this->getUser()->id
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
     * Get One Row using target_id
     *
     * @param string uuid $targetId
     * @return array|null
     */
    public function getOneRow(string $targetId) :? array
    {
        return parent::getOne([
            'id' => $targetId
        ]);
    }

    /**
     * Get All User Contact Rows
     *
     * @return array|null
     */
    public function getMyRows() :? array
    {
        return parent::getMy();
    }

    /**
     * Update One Row using target_id
     *
     * @param string uuid $targetId
     * @return array|null
     */
    public function updateOneRow(string $targetId) :? array
    {
        return parent::updateOne([
            'id' => $targetId
        ]);
    }
}
