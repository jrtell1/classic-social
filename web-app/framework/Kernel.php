<?php

namespace Framework;

use Framework\Router\Router;
use Framework\Templating\Templater;
use Framework\Templating\View;
use Symfony\Component\HttpFoundation\Request;

class Kernel
{
    private Router $router;
    private Templater $templater;
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

        if ($response instanceof View) {
            $content = $this->templater->render($response->getView(), $response->getData());
            $response = new Response($content);
        } else if (!$response instanceof Response) {
            return new Response('Controller must return a Response or a View', 500);
        }

        return $response;
    }

    public function boot(): void
    {
        $this->setupContainer();
        $this->setupRouter();
        $this->setupViews();
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

    private function setupViews(): void
    {
        $this->templater = new Templater();
        $this->templater->loadTemplates();
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
