<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Contact;

use Meetingg\Models\Group;
use Meetingg\Validators\GroupValidator;
use Meetingg\Controllers\Auth\ApiModelController;

/**
 *  Landing Index Controller
 */
class GroupController extends ApiModelController
{
    /** @var ROW_TITLE */
    const ROW_TITLE = 'group';

    /** @var DATA_ASSIGN */
    const DATA_ASSIGN = [ 'title' ];

    /** @var FOREIGN_KEYS */
    const FOREIGN_KEYS = [ 'user_id' ];

    /** @var PRIMARY_KEYS */
    const PRIMARY_KEYS = [ 'id' ];

    /** @var DATA_ASSIGN_UPDATE */
    const DATA_ASSIGN_UPDATE = true;

    /** @var INSERT_ROW_ACTIVE */
    const INSERT_ROW_ACTIVE = true;


    /** @var VALIDATOR */
    const VALIDATOR = GroupValidator::class;

    /** @var MODEL */
    const MODEL = Group::class;

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
