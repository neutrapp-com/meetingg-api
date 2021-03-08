<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Discussion;

use Meetingg\Controllers\Auth\ApiModelController;
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

    const INSERT_ONE = false;
    const UPDATE_ONE = false;
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

    /**
     * Delete One Row using target_id
     *
     * @param string uuid $targetId
     * @return array|null
     */
    public function deleteOneRow(string $targetId) :? array
    {
        return parent::deleteOne([
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
}
