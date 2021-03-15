<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Geo;

use Meetingg\Library\Country;
use Meetingg\Controllers\BaseController;

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
        $countries = [];
     
        foreach (Country::all() as $id => $country) {
            $countries[] = [
                'id'=>$id,
                'title'=>$country['name']
            ];
        }
     
        return ['data'=>
            $countries
        ];
    }
}
