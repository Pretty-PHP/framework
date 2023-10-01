<?php 

namespace PrettyPHP\Router;

use PrettyPHP\Http\Request;
use PrettyPHP\Router\Route;

class RouteService {
	
    public function loadRoutes(array $routeFiles = []) {
        foreach ($routeFiles as $value) {
            require_once $value;
        }
    }
    
    public function __construct() {
        //require_once 'ViewProvider.php';
                
        $path = Route::currentPath();
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset(Route::$routes['ANY'][$path])) {
            $this->makeRoute('ANY', $path);
        } else if (isset(Route::$routes[$method][$path])) {
            $this->makeRoute($method, $path);
        } else {
            $this->makeRoute('ANY', 'error_404');
        }
    }
    
    private function makeRoute(string $method, string $path){
        $route = Route::$routes[$method][$path];
        $request = new Request();

        /* if(!empty($route['middleware'])){
            foreach($route['middleware'] as $name){
                $middleware = MiddlewareProvider::findMiddleware($name);
                
                if($middleware != null && !$middleware->validate()){
                    echo $middleware->name() . " not validated";
                    return;
                }
            }
        }*/

        if (!is_null($route) && is_callable($route['callback'])) {
            $route['callback']($request);
        } else if (!is_null($route) && is_string($route['callback'])) {
           //ControllerProvider::startController($route['callback'], $request);
        }
    }
}