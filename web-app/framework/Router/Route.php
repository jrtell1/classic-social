<?php

namespace Framework\Router;

class Route {
    public function __construct(
        public readonly string $path,
        public readonly string $method,
        public readonly string $controller,
        public readonly string $action
    ) {}
}
