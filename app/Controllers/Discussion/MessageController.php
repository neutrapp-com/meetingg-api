<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Discussion;

use Meetingg\Exception\PublicException;
use Meetingg\Http\StatusCodes;
use Meetingg\Library\Permissions;
use Meetingg\Models\Message;
use Meetingg\Validators\MessageValidator;

class MessageController extends DiscussionController
{

      /** @var ROW_TITLE */
    const ROW_TITLE = 'message';

    /** @var DATA_ASSIGN */
    const DATA_ASSIGN = [ 'content' ];
  
    /** @var FOREIGN_KEYS */
    const FOREIGN_KEYS = [ 'user_id' , 'discussion_id' ];
  
    /** @var PRIMARY_KEYS */
    const PRIMARY_KEYS = [ 'id' , 'discussion_id' ];
  
    /** @var DATA_ASSIGN_UPDATE */
    const DATA_ASSIGN_UPDATE = true;
  
    /** @var INSERT_ROW_ACTIVE */
    const INSERT_ROW_ACTIVE = true;
  
  
    /** @var VALIDATOR */
    const VALIDATOR = MessageValidator::class;
  
    /** @var MODEL */
    const MODEL = Message::class;

    /** @var ROWS_LIMIT */
    const ROWS_LIMIT = 20;
  
    /**
     * Foreign Keys
     *
     * @return array
     */
    protected function foreignkeys() : array
    {
        return [
              'user_id'=> $this->getUser()->id,
              'discussion_id'=> $this->router->getParams()['discussion'],
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
              ],
              'order'=>'created_at DESC',
              'limit'=> self::ROWS_LIMIT
          ];
    }

    /**
     * New Message
     *
     * @param string $discussionId
     * @return array|null
     */
    public function sendMesage(string $discussionId) :? array
    {
        $discussion = $this->getDiscussion($discussionId);
        $user = $this->getUser();

        $permissions =  0x0;
        $users = $discussion->DiscussionUsers ?? [];
        foreach ($users as $duser) {
            if ($duser->user_id == $user->id) {
                $permissions = $duser->permissions;
            }
        }

        if (!in_array(true, [
            ($permissions & Permissions::ADMINISTRATOR) == Permissions::ADMINISTRATOR,
            ($permissions & Permissions::SEND_MESSAGES) == Permissions::SEND_MESSAGES,
        ])) {
            throw new PublicException("You dont have permission to send messages", StatusCodes::HTTP_FORBIDDEN);
        }

        return [
            'row'=> $this->newOne()
        ];
    }
}
