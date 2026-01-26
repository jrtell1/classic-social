<?php

namespace Framework;

use Symfony\Component\HttpFoundation\Request;

class Router {
    private array $routes = [];

    public function addRoute(Route $route): void
    {
        $this->routes[] = $route;
    }

    public function match(Request $request): ?MatchedRoute
    {
        $requestPath = $request->getPathInfo();
        $requestMethod = $request->getMethod();

        foreach ($this->routes as $route) {
            // First check if HTTP method matches
            if ($route->method !== $requestMethod) {
                continue;
            }

            // Build regex pattern and extract parameter names
            $pattern = $this->buildRegexPattern($route->path);

            // Try to match the request path against the regex
            if (preg_match($pattern, $requestPath, $matches)) {
                $paramNames = $this->extractParameterNames($route->path);

                // Map parameter names to their matched values
                $parameters = [];
                for ($i = 0; $i < count($paramNames); $i++) {
                    $paramName = $paramNames[$i];

                    // $matches[0] is the full match, so we start from index 1
                    $paramValue = $matches[$i + 1];
                    $parameters[$paramName] = $paramValue;
                }

                return new MatchedRoute($route, $parameters);
            }
        }

        return null; // No match found
    }

    private function extractParameterNames(string $pattern): array
    {
        preg_match_all('#\{([^}]+)\}#', $pattern, $matches);

        return $matches[1];
    }

    private function buildRegexPattern(string $pattern): string
    {
        // Replace {param} placeholders with temporary markers
        $regex = preg_replace('#\{([^}]+)\}#', '__PARAM_PLACEHOLDER__', $pattern);

        // Escape special regex characters
        $regex = preg_quote($regex, '#');

        // Replace temporary markers with regex capture groups
        $regex = str_replace('__PARAM_PLACEHOLDER__', '([^/]+)', $regex);

        // Add anchors to match the entire string
        return '#^' . $regex . '$#';
    }
}
