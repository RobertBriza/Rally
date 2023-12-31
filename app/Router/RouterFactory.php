<?php

declare(strict_types=1);

namespace app\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {
        $router = new RouteList;

        $router->addRoute('/', 'Rally:Teams:list');

        $router->addRoute('<module>/<presenter>/<action>[/<id>]');

        return $router;
    }
}
