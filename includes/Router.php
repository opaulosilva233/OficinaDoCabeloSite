<?php

class Router {
    private $routes = [];

    public function add($route, $controller, $action) {
        $this->routes[$route] = ['controller' => $controller, 'action' => $action];
    }

    public function dispatch($route) {
        if (array_key_exists($route, $this->routes)) {
            $controllerName = $this->routes[$route]['controller'];
            $actionName = $this->routes[$route]['action'];

            require_once __DIR__ . "/controllers/$controllerName.php";
            $controller = new $controllerName();
            $controller->$actionName();
        } else {
            // Default 404 or redirect to home
            header("HTTP/1.0 404 Not Found");
            echo "Página não encontrada.";
        }
    }
}
?>
