<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Contact;

use Meetingg\Models\Contact;
use Meetingg\Validators\ContactValidator;
use Meetingg\Controllers\Auth\ApiModelController;

/**
 *  Landing Index Controller
 */
class ContactController extends ApiModelController
{
    /** @var ROW_NAME */
    const ROW_NAME = 'contact';

    /** @var DATA_ASSIGN */
    const DATA_ASSIGN = [ 'target_id', 'title',  'starred', 'blocked' ];

    /** @var FOREIGN_KEYS */
    const FOREIGN_KEYS = [ 'user_id' , 'target_id' ];

    /** @var PRIMARY_KEYS */
    const PRIMARY_KEYS = [ 'user_id' , 'target_id' ];

    /** @var DATA_ASSIGN_UPDATE */
    const DATA_ASSIGN_UPDATE = true;

    /** @var NEW_ROW_ACTIVE */
    const NEW_ROW_ACTIVE = true;


    /** @var VALIDATOR */
    const VALIDATOR = ContactValidator::class;

    /** @var MODEL */
    const MODEL = Contact::class;

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
            'user_id'=> $this->getUser()->id,
            'target_id' => $targetId
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
            'user_id'=> $this->getUser()->id,
            'target_id' => $targetId
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
            'user_id'=> $this->getUser()->id,
            'target_id' => $targetId
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
