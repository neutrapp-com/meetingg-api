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
    /** @var ROW_NAME */
    const ROW_NAME = 'group';

    /** @var DATA_ASSIGN */
    const DATA_ASSIGN = ['title'];

    /** @var FOREIGN_KEYS */
    const FOREIGN_KEYS = [ 'user_id'];

    /** @var DATA_ASSIGN_UPDATE */
    const DATA_ASSIGN_UPDATE = true;

    /** @var NEW_ROW_ACTIVE */
    const NEW_ROW_ACTIVE = true;


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
}
