<?php

namespace Router;

class Router {

    public static string $root;
    public static array $routes = [];
    public static array $methods = [];
    public static array $callbacks = [];
    public static array $patterns = [
        ':any' => '[a-zA-Z0-9-_=+*!@&$.,:; ]+',
        ':num' => '[0-9]+',
        ':str' => '[a-zA-Z- ]+',
        ':all' => '.*'
    ];
    public static array $defaultRoutes = [];
    public static mixed $error_callback = null;
    protected static array $defaultPages = ['/', '404'];

    public static function get(string $url, string $path): void
    {
        self::typeMethods(__FUNCTION__, $url, $path);
    }
    public static function post(string $url, string $path): void
    {
        self::typeMethods(__FUNCTION__, $url, $path);
    }
    public static function any(string $url, string $path): void
    {
        self::typeMethods(__FUNCTION__, $url, $path);
    }

    public static function typeMethods(string $type, string $url, string $path): void
    {
        self::$methods[] = strtoupper($type); // $_SERVER['REQUEST_METHOD'])
        self::$routes[] = str_starts_with($url, '/') ? $url : '/' . $url;
        self::$callbacks[] = $path;

        self::setDefaultRoutes($url, $path);
    }

    public static function dispatch(): void
    {
        $uri = self::createURI();
        $method = self::getMethod();

        list ($searches, $replaces) = self::extractFromPatterns(static::$patterns);

        self::$routes = preg_replace('/\/+/', '/', self::$routes);

        if (in_array($uri, self::$routes)) {
            $found_route = self::requestWithHardRoute($uri, $method);
        } else {
            $found_route = self::requestWithParameters($searches, $replaces, $uri, $method);
        }

        self::errorRouteNotFound($found_route, $uri);
    }

    private static function extractFromPatterns($patterns): array
    {
        return [array_keys($patterns), array_values($patterns)];
    }

    protected static function createURI(): string
    {
        $uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        if (!empty(self::$root) && self::$root !== '/') {
            self::$root = rtrim(self::$root, '/');
            if (self::$root === $uri) {
                $uri = '/';
            } else {
                // Remove the root directory from uri:
                $uri = substr_replace($uri, '', strpos($uri, self::$root), strlen(self::$root));
            }
        }
        return $uri;
    }
    protected static function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    protected static function requestWithParameters($searches, $replaces, $uri, $method): bool
    {
        $found_route = false;
        $pos = 0;
        foreach (self::$routes as $route) {
            if (str_contains($route, ':')) {
                $route = str_replace($searches, $replaces, $route);
            }

            if (preg_match('#^' . $route . '$#', $uri, $matched)) {
                if (self::$methods[$pos] == $method || self::$methods[$pos] == 'ANY') {
                    $found_route = true;

                    array_shift($matched);

                    if (!is_object(self::$callbacks[$pos])) {
                        self::controllerActivate(self::$callbacks[$pos], $matched);
                    } else {
                        call_user_func_array(self::$callbacks[$pos], $matched);
                    }
                }
            }

            $pos++;
        }

        return $found_route;
    }
    protected static function requestWithHardRoute($uri, $method): bool
    {
        $found_route = false;
        $route_pos = array_keys(self::$routes, $uri);
        foreach ($route_pos as $route) {

            if (self::$methods[$route] == $method || self::$methods[$route] == 'ANY') {
                $found_route = true;

                if (!is_object(self::$callbacks[$route])) {

                    $parts = explode('/', self::$callbacks[$route]);
                    $last = end($parts);
                    $segments = explode('@', $last);
                    $controller = new $segments[0]();

                    $controller->{$segments[1]}();

                } else {

                    call_user_func(self::$callbacks[$route]);

                }

            }
        }

        return $found_route;
    }

    protected static function controllerActivate($callback, $matched = null): void
    {
        $parts = explode('/', $callback);
        $last = end($parts);
        $segments = explode('@',$last);
        $controller = new $segments[0]();

        if (method_exists($controller, $segments[1])) {
            if ($matched !== null)
                call_user_func_array(array($controller, $segments[1]), $matched);
            else
                call_user_func(array($controller, $segments[1]));
        } else {
            echo "Controller or action not found";
        }
    }

    private static function setDefaultRoutes($url, $path): void
    {
        if (in_array($url, self::$defaultPages)) {
            self::$defaultRoutes[$url] = $path;
        }
    }

    private static function errorRouteNotFound($found_route, $uri): void
    {
        if (!$found_route) {

            if (empty($uri)) {
                self::controllerActivate(self::$defaultRoutes['/']);
            } else {
                self::showError404();
            }

        }
    }

    private static function showError404(): void
    {
        header("HTTP/1.1 404 Not Found");
        self::controllerActivate(self::$defaultRoutes['404']);
    }

}