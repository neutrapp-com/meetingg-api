<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Group;

use Meetingg\Models\Group;
use Meetingg\Validators\GroupValidator;
use Meetingg\Controllers\Auth\ApiModelController;

/**
 *  Landing Index Controller
 */
class GroupController extends ApiModelController
{
    /** @var DATA_ASSIGN */
    const DATA_ASSIGN = [
        'title'
    ];

    /** @var FORGING_KEYS */
    const FORGING_KEYS = [
        'user_id'
    ];

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
     * Foreign Keys
     *
     * @return array
     */
    protected function modelFindParams() : array
    {
        return [
            'user_id'=> $this->getUser()->id
        ];
    }
}
