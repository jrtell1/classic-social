<?php

use App\Controllers\HomeController;
use Framework\Route;
use Framework\Router;

return function (Router $router) {
    $router->addRoute(new Route('/', 'GET', HomeController::class, 'index'));
};
