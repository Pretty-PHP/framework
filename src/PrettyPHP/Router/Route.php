<?php 

namespace PrettyPHP\Router;

class Route {
    
    public static $routes = [
        'GET' => [],
        'POST' => [],
        'PATCH' => [],
        'PUT' => [],
        'OPTIONS' => [],
        'DELETE' => [],
        'HEAD' => []
    ];
    
    private static function addRoute(string $method, string $path, mixed $callback, array $middleware = []) {
        self::$routes[$method] = array_merge(self::$routes[$method], [$path => ['middleware' => $middleware, 'callback' => $callback]]);
    }
    
    public static function redirect($path) {
	if($path == self::currentPath())
            return;
	    
        $url = $_SERVER['REQUEST_URI'];
        if(!str_ends_with($url, "/")) $url = $url."/";
        
        if(str_starts_with($path, "/")) $path = substr($path, 1);

        header('Location: ' . $url . $path);
    }

    public static function refresh() {
        self::redirect(self::currentPath());
    }
    
    public static function get404(mixed $callback, array $middleware = []) {
        self::addRoute('GET', 'error_404', $callback, $middleware);
    }

    public static function get(string $path, mixed $callback, array $middleware = []) {
        self::addRoute('GET', $path, $callback, $middleware);
    }
    
    public static function post(string $path, mixed $callback, array $middleware = []) {
        self::addRoute('POST', $path, $callback, $middleware);
    }
    
    public static function patch(string $path, mixed $callback, array $middleware = []) {
        self::addRoute('PATCH', $path, $callback, $middleware);
    }

    public static function put(string $path, mixed $callback, array $middleware = []) {
        self::addRoute('PUT', $path, $callback, $middleware);
    }

    public static function options(string $path, mixed $callback, array $middleware = []) {
        self::addRoute('OPTIONS', $path, $callback, $middleware);
    }

    public static function delete(string $path, mixed $callback, array $middleware = []) {
        self::addRoute('DELETE', $path, $callback, $middleware);
    }

    public static function head(string $path, mixed $callback, array $middleware = []) {
        self::addRoute('HEAD', $path, $callback, $middleware);
    }

    public static function currentPath() : string {
        $request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $script_name = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
        
        $parts = array_diff_assoc($request_uri, $script_name);
        
	    if (empty($parts)) return '';
        
        $path = implode('/', $parts);
        
        if (($position = strpos($path, '?')) !== FALSE) $path = substr($path, 0, $position);
        
        return strtolower($path);
    }
    
}