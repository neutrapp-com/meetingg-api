<?php
declare(strict_types=1);

namespace Meetingg\Controllers;

/**
 *  Landing Index Controller
 */
class IndexController extends BaseController
{
    /**
     * Landing Index Action
     *
     * @return void
     */
    public function index()
    {
        $routes = array_map(function ($item) {
            return $item->getPattern();
        }, $this->router->getRoutes());
        sort($routes);

        return [
            "routes" =>  $routes
        ];
    }
}
