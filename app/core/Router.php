<?php

namespace App\Core;

use App\Controllers\HttpErrorController;
use App\Helpers\GeneralHelper;

class Router
{
    private $routes;
    private $groupPrefix;
    private $middlewareStack;
    public $helper;

    public function __construct(GeneralHelper $helper)
    {
        $this->routes = [];
        $this->groupPrefix = '';
        $this->middlewareStack = [];
        $this->helper = $helper;
    }

    // redirects to the desired location
    public function redirect($location)
    {
        header("Location: $location");
        exit();
    }

    // Add middleware to the middleware stack
    public function middleware($middleware)
    {
        if (is_array($middleware)) {
            $this->middlewareStack = array_merge($this->middlewareStack, $middleware);
        } else {
            $this->middlewareStack[] = $middleware;
        }
    }

    public function group($prefix, $callback, $middleware = [])
    {
        // Save the current prefix and middleware stack
        $previousPrefix = $this->groupPrefix;
        $previousMiddleware = $this->middlewareStack;

        // $this->helper->dd([$previousPrefix, $prefix]);

        // Update prefix and merge new middleware with current stack
        $this->groupPrefix = $previousPrefix . $prefix;

        // Add any middleware provided for this group
        if (!empty($middleware)) {
            $this->middlewareStack = array_merge($this->middlewareStack, $middleware);
        }

        // Call the callback, which adds routes to the group
        $callback($this);

        // Restore previous prefix and middleware stack after group is done
        $this->groupPrefix = $previousPrefix;
        $this->middlewareStack = $previousMiddleware;
    }


    public function get($path, $callback)
    {
        $this->routes['GET'][$this->groupPrefix . $path] = [
            'callback' => $callback,
            'middleware' => $this->middlewareStack
        ];
    }

    public function post($path, $callback)
    {
        $this->routes['POST'][$this->groupPrefix . $path] = [
            'callback' => $callback,
            'middleware' => $this->middlewareStack
        ];
    }

    public function put($path, $callback)
    {
        $this->routes['PUT'][$this->groupPrefix . $path] = [
            'callback' => $callback,
            'middleware' => $this->middlewareStack
        ];
    }

    public function delete($path, $callback)
    {
        $this->routes['DELETE'][$this->groupPrefix . $path] = [
            'callback' => $callback,
            'middleware' => $this->middlewareStack
        ];
    }

    public function dispatch()
    {
        $requestUri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $requestUri = $requestUri === '' ? '/' : $requestUri;
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$requestMethod] as $route => $details) {
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remove the full match
                $callback = $details['callback'];
                $middleware = $details['middleware'];

                // Run middleware stack before executing the callback
                foreach ($middleware as $middlewareClass) {
                    $middlewareInstance = new $middlewareClass();
                    if (!$middlewareInstance->handle()) {
                        // Redirect to the previous URL
                        if (isset($_SERVER['HTTP_REFERER'])) {
                            header("Location: {$_SERVER['HTTP_REFERER']}");
                        } else {
                            header("Location: /"); // Default to homepage if HTTP_REFERER is not available
                        }
                        exit(); // Make sure to exit after the redirect
                    }
                }

                // If callback is an array (controller and method)
                if (is_array($callback) && count($callback) == 2) {
                    list($controllerName, $methodName) = $callback;
                    $controller = ControllerFactory::create($controllerName);
                    $controller = $controller ?? new $controllerName();
                    call_user_func_array([$controller, $methodName], $matches);
                } elseif (is_callable($callback)) {
                    call_user_func_array($callback, $matches);
                } else {
                    header("HTTP/1.0 500 Internal Server Error");
                    echo "Invalid route callback.";
                }
                return;
            }
        }

        // If no route matches
        header("HTTP/1.0 404 Not Found");
        $controller = new HttpErrorController();
        $controller->render404Page();
    }
}
