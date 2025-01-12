<?php
$routes = [
    "Site%20Hipermarket(1)/Hipermarket/items/index" => ["ItemController", "index"],
    "Site%20Hipermarket(1)/Hipermarket/items/show" => ["ItemController", "show"],
    "Site%20Hipermarket(1)/Hipermarket/items/edit" => ["ItemController", "edit"],
    "Site%20Hipermarket(1)/Hipermarket/items/create" => ["ItemController", "create"],
    "Site%20Hipermarket(1)/Hipermarket/items/delete" => ["ItemController", "delete"],

    "Site%20Hipermarket(1)/Hipermarket/users/index" => ["UserController", "index"],
    "Site%20Hipermarket(1)/Hipermarket/users/show" => ["UserController", "show"],
    "Site%20Hipermarket(1)/Hipermarket/users/edit" => ["UserController", "edit"],
    "Site%20Hipermarket(1)/Hipermarket/users/create" => ["UserController", "create"],
    "Site%20Hipermarket(1)/Hipermarket/users/delete" => ["UserController", "delete"]
    
    "Site%20Hipermarket(1)/Hipermarket/auth/login" => ["AuthController", "login"],
    "Site%20Hipermarket(1)/Hipermarket/auth/logout" => ["AuthController", "logout"],
    "Site%20Hipermarket(1)/Hipermarket" => ["AuthController", "landing_page"],
];

class Router {
    private $uri;

    public function __construct() {
        // Get the current URI
        $this->uri = trim(parse_url($_SERVER["REQUEST_URI"],PHP_URL_PATH), "/");
    }

    public function direct() {
        global $routes;
   
        if (array_key_exists($this->uri, $routes)) {

            // Get the controller and method
            [$controller, $method] = $routes[$this->uri];

            // Load the controller file if it hasn't been autoloaded
            require_once "app/controllers/{$controller}.php";

            // Call the method
            return $controller::$method();
        }
        echo ("ruta nu a fost gasita");
        require_once "app/views/404.php";
    }
}

?>