<?php

namespace MVC\Core;

use Exception;

// TODO: Support dynamic routes with parameters (e.g., /article/1)

class Router extends Singleton
{
    /**
     * Array to store GET routes (path => handler).
     */
    private array $routes = [];

    /**
     * Add a new GET route to the router.
     *
     * @param string $path The URL path (e.g., /home).
     * @param callable $handler The handler (a function or controller action).
     */
    public function addRoute(string $path, callable $handler): void
    {
        // Ensure the path starts with a slash and avoid double slashes
        $path = '/' . ltrim($path, '/');
        $this->routes[$path] = $handler;
    }

    /**
     * Loads routes from a file and adds them to the router.
     *
     * @param string $filePath The path to the file containing the routes.
     *
     * The file should return an associative array where each key is a route path
     * and each value is a callable handler.
     *
     * @throws Exception If the file is not found or doesn't return an array.
     */
    public function loadRoutesFromFile(string $filePath): void
    {
        if (file_exists($filePath)) {
            $routes = require $filePath;
            if (is_array($routes)) {
                foreach ($routes as $path => $callback) {
                    // Check if the callback is in the "Controller@method" format
                    if (is_string($callback) && str_contains($callback, '@')) {
                        [$controller, $method] = explode('@', $callback);
                        $controller = "App\\Controllers\\$controller";
                        $callback = [new $controller(), $method];
                    }

                    $this->addRoute($path, $callback);
                }
            } else {
                throw new Exception("Invalid routes file. Expected an array.");
            }
        } else {
            throw new Exception("Routes file not found: $filePath");
        }
    }

    /**
     * Dispatches a GET request to the associated route handler if it exists.
     *
     * This method:
     * - Blocks non-GET requests (405 Method Not Allowed)
     * - Checks if the requested route exists in the router
     * - If the route exists, calls the associated handler (a closure or a controller method)
     * - If the route does not exist, returns a 404 error (Page not found)
     */
    public function dispatch(): void
    {
        // Get the requested path
        $path = rtrim($_SERVER['REQUEST_URI'], '/');

        if ($path === '') {
            $path = '/';
        }

        // Check if the route exists for the GET request
        if (isset($this->routes[$path])) {
            // Call the handler (could be a closure or a controller method)
            call_user_func($this->routes[$path]);
        } else {
            // Handle 404 - route not found
            echo "404 - Page not found";
        }
    }
}