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
    /** @var ROW_TITLE */
    const ROW_TITLE = 'Contact';

    /** @var DATA_ASSIGN */
    const DATA_ASSIGN  = [ 'target_id', 'title',  'starred', 'blocked' ];

    /** @var FOREIGN_KEYS */
    const FOREIGN_KEYS = [ 'user_id' , 'target_id' ];

    /** @var PRIMARY_KEYS */
    const PRIMARY_KEYS = [ 'user_id' , 'target_id' ];

    /** @var DATA_ASSIGN_UPDATE */
    const DATA_ASSIGN_UPDATE = true;

    /** @var INSERT_ROW_ACTIVE */
    const INSERT_ROW_ACTIVE = true;

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
            'user_id = :user_id:',
            'bind'=>  [
                'user_id'=> $this->getUser()->id
            ]
        ];
    }

    /**
     * New One Row
     *
     * @param string uuid $targetId
     * @return array|null
     */
    public function newOneRow() :? array
    {
        return [
            'row' => parent::newOne()->getProfile()
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
                'target_id' => $targetId
            ])->getProfile()
        ];
    }

    /**
     * Update One Row using target_id
     *
     * @param string uuid $targetId
     * @return array|null
     */
    public function updateOneRow(string $targetId) :? array
    {
        parent::updateOne([
            'target_id' => $targetId
        ]);

        return [
            'update' => true
        ];
    }

    /**
     * Delete One Row using target_id
     *
     * @param string uuid $targetId
     * @return array|null
     */
    public function deleteOneRow(string $targetId) :? array
    {
        parent::deleteOne([
            'target_id' => $targetId
        ]);
        
        return [
            'delete' => true
        ];
    }

    /**
     * Get All User Contact Rows
     *
     * @return array|null
     */
    public function getMyRows() :? array
    {
        $this->di->setShared('minimized_content', true);
        $rows = parent::getMy();
        $items = [];

        foreach ($rows as $contact) {
            array_push($items, $contact->getProfile());
        }

        return [
            'rows' => $items
        ];
    }
}
