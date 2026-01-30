<?php

namespace Framework\Router;

class MatchedRoute {
    public function __construct(
        public readonly Route $route,
        public readonly array $parameters = []
    ) {}

    public function getParameter(string $name, mixed $default = null): mixed
    {
        return $this->parameters[$name] ?? $default;
    }
}
