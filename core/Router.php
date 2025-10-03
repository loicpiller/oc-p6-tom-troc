<?php

namespace MVC\Core;

use Exception;
use ReflectionMethod;

class Router extends Singleton
{
    /**
     * Array to store GET routes (path => handler).
     */
    private array $routes = [];
    private array $dynamicRoutes = [];

    /**
     * Converts a route path with parameters to a regex pattern.
     *
     * @param string $path The route path with parameters (e.g., /article/{id}).
     * @param array $paramNames An array to store the parameter names.
     * @return string The regex pattern (e.g., /^\/article\/([^/]+)$/).
     */
    private function convertToRegex(string $path, array &$paramNames): string
    {
        return '#^' . preg_replace_callback('/\{([^}]+)\}/', function ($matches) use (&$paramNames) {
                $paramNames[] = $matches[1];
                return '([^/]+)';
            }, $path) . '$#';
    }

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

        if (str_contains($path, '{')) {
            $paramNames = [];
            $regex = $this->convertToRegex($path, $paramNames);
            $this->dynamicRoutes[] = ['pattern' => $regex, 'handler' => $handler, 'paramNames' => $paramNames];
        } else {
            $this->routes[$path] = $handler;
        }
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
     * Serves a static file.
     *
     * If the file exists and is a file (not a directory), it will be served with the correct Content-Type.
     * If the file doesn't exist, a 404 error will be sent and the code will exit.
     *
     * @param string $path The URL path of the file to serve (e.g., /public/css/style.css).
     */
    private function serveStaticFile(string $path): void
    {
        $filePath = __DIR__ . "/.." . $path;

        if (file_exists($filePath) && is_file($filePath)) {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);

            // Manual mapping of Content-Type
            $mimeTypes = [
                'css'  => 'text/css',
                'js'   => 'application/javascript',
                'json' => 'application/json',
                'webp' => 'image/webp',
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png'  => 'image/png',
                'gif'  => 'image/gif',
                'svg'  => 'image/svg+xml',
                'woff' => 'font/woff',
                'woff2'=> 'font/woff2',
                'ttf'  => 'font/ttf',
                'eot'  => 'application/vnd.ms-fontobject',
                'otf'  => 'font/otf',
                'mp4'  => 'video/mp4',
                'webm' => 'video/webm',
                'ogg'  => 'audio/ogg',
            ];

            // Uses the mapping or falls back to `mime_content_type`
            $mimeType = $mimeTypes[$extension] ?? mime_content_type($filePath);

            header("Content-Type: " . $mimeType);
            header("Content-Length: " . filesize($filePath));

            readfile($filePath);
            exit;
        }

        http_response_code(404);
        echo "404 - File not found";
        exit;
    }

    /**
     * Calls a handler and passes the parameters to it.
     *
     * If the handler is a controller method, this method will also convert the parameters to the correct types.
     *
     * @param callable $handler The handler to call.
     * @param array $params The parameters to pass to the handler.
     */
    private function callHandler(callable $handler, array $params): void
    {
        if (is_array($handler) && count($handler) === 2) {
            [$controller, $method] = $handler;
            $reflection = new ReflectionMethod($controller, $method);
            $parameters = $reflection->getParameters();

            foreach ($parameters as $param) {
                $name = $param->getName();
                if (isset($params[$name]) && $param->hasType()) {
                    $type = $param->getType()->getName();
                    if ($type === 'int') {
                        $params[$name] = (int) $params[$name];
                    }
                }
            }
        }

        call_user_func_array($handler, $params);
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

        // Serve static files
        if (str_starts_with($path, '/public')) {
            $this->serveStaticFile($path);
            return;
        }

        // Exact match route
        if (isset($this->routes[$path])) {
            call_user_func($this->routes[$path]);
            return;
        }

        // Dynamic route matching
        foreach ($this->dynamicRoutes as $route) {
            if (preg_match($route['pattern'], $path, $matches)) {
                array_shift($matches);
                $params = array_combine($route['paramNames'], $matches);
                $this->callHandler($route['handler'], $params);
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 - Page not found";
    }
}