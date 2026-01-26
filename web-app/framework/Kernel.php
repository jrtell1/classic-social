<?php

namespace Framework;

use Symfony\Component\HttpFoundation\Request;

class Kernel
{
    private Router $router;
    private Container $container;

    public function handle(Request $request): Response
    {
        $matchedRoute = $this->router->match($request);

        if (!$matchedRoute) {
            return new Response('Route not found', 404);
        }

        $controller = $this->container->build($matchedRoute->route->controller);

        if (!method_exists($controller, $matchedRoute->route->action)) {
            return new Response('Method not found on route', 404);
        }

        $response = $controller->{$matchedRoute->route->action}($request, $matchedRoute->parameters);

        if (!$response instanceof Response) {
            return new Response('Controller must return a Response', 500);
        }

        return $response;
    }

    public function boot(): void
    {
        $this->setupContainer();
        $this->setupRouter();
//        $this->setupDatabase();
    }

    public function terminate(): void
    {
        // Do something on request termination
    }

    public function shutdown(): void
    {
        // Do something on server shutdown
    }

    private function setupContainer(): void
    {
        $this->container = new Container();

        $configurator = require __DIR__ . '/../config/services.php';
        $configurator($this->container);
    }

    private function setupRouter(): void
    {
        $this->router = new Router();
        $routeLoader = require __DIR__ . '/../config/routes.php';
        $routeLoader($this->router);
    }

    private function setupDatabase(): void
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => 'mysql',
            'database' => 'default',
            'username' => 'default',
            'password' => 'secret',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $capsule->setAsGlobal();
    }
}
