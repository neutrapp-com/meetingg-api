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

    /** @var ROW_KEYS */
    const ROW_KEYS = ['id','title','content','status','created_at','sender_id'];

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
     * Modal Find Params
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
     * Get All User Rows
     *
     * @return array|null
     */
    public function getMyRows() :? array
    {
        $rows = parent::getMy();
        $items = [];
        
        foreach ($rows as $row) {
            $items[] = $row->getArray(self::ROW_KEYS);
        }

        return [
            'rows'=> $items
        ];
    }

    /**
     * Get One Row using id
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
            ])->getArray(self::ROW_KEYS)
        ];
    }

    /**
     * Update One Row using id
     *
     * @param string uuid $targetId
     * @return array|null
     */
    public function updateOneRow(string $targetId) :? array
    {
        parent::updateOne([
            'id' => $targetId
        ]);

        return [
            'update' => true
        ];
    }
}
