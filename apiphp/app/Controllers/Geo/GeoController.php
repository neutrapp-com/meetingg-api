<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Geo;

use Meetingg\Controllers\BaseController;
use Meetingg\Library\Country;

/**
 *  Landing Static Controller
 */
class GeoController extends BaseController
{
    const COUNTRY_COLUMNS = ['id','title'];


    public function onConstruct()
    {
        $this->getDi()->setShared('minimized_content', true);
    }

    /**
     * Countries Static Action
     *
     * @return array|null
     */
    public function countries() :? array
    {
        return ['data'=>
            Country::all()
        ];
    }
}
