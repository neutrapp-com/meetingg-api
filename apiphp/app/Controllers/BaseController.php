<?php
declare(strict_types=1);

namespace Meetingg\Controllers;

use Phalcon\Mvc\Controller;

use Meetingg\Interfaces\SharedConstInterface;

class BaseController extends Controller implements SharedConstInterface
{

    /**
     * Index : Get List of Routes
     *
     * @return array
     */
    public function index() : array
    {
        $matched = $this->router->getMatchedRoute();
        $matched = $matched->getPattern() ?? "/";
        
        return [
            'routes'=> array_values(array_filter(self::getRoutes($this), function ($item) use ($matched) {
                return strpos($item, $matched) !== false;
            }))
        ];
    }

    /**
     * Get List of Routes
     *
     * @param Micro $app
     * @return array|null
     */
    public static function getRoutes(BaseController $controller) :? array
    {
        $routes = array_map(function ($item) {
            return $item->getPattern();
        }, $controller ->router->getRoutes());
        sort($routes);

        return $routes;
    }
}
