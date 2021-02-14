<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Meeting;

use Meetingg\Controllers\BaseController;
use Meetingg\Models\Meeting;

/**
 *  Landing Index Controller
 */
class MeetingController extends BaseController
{

    /**
     * Get Meeting by id
     *
     * @param string $id
     * @return array|null
     */
    public function getMeeting(string $id) :? array
    {
        $meeting = Meeting::findFirstById($id);

        return ["data" => self::filterData($meeting->toArray(), ['password']) ];
    }
}
