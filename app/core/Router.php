<?php
class Router{
    public function display(){
        $url = filter_var($_GET['action'] ?? 'auth'.FILTER_SANTIZE_URL);
        $url = explode("/", trim($url, "/"));
        $controllerName = ucfirst($url[0]) . "Controller";
        $method = $url[1] ?? "index";
        $controllerPath = "../app/Controllers/$controllerName.php";
        if(!file_exists($controllerPath)){
            require_once __DIR__ . "/../Controllers/NotfoundController.php";
            $error = new NotfoundController();
            $error->index();
            return;
        }
        require $controllerPath;
        $controller = new $controllerName();
        if(!method_exists($controller, $method)){
            require_once __DIR__ . "/../Controllers/NotfoundController.php";
            $error = new NotfoundController();
            $error->index();
            return;
        }
        $controller->$method();
    }
}
?>