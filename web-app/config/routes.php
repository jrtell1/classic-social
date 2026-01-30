<?php

use App\Controllers\HomeController;
use Framework\Router\Route;
use Framework\Router\Router;

return function (Router $router) {
    $router->addRoute(new Route('/', 'GET', HomeController::class, 'index'));
};
