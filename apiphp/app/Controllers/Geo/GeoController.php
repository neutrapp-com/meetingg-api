<?php
declare(strict_types=1);

namespace Meetingg\Controllers\Geo;

use Meetingg\Models\Country;
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
        return ['data'=>
            array_map(function ($item) {
                return array_filter($item, function ($key) {
                    return in_array($key, self::COUNTRY_COLUMNS);
                }, ARRAY_FILTER_USE_KEY);
            }, Country::find()->toArray(true))
        ];
    }
}
