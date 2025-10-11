<?php
class Router {
    public static function route($url) {
        $controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';
        $methodName = !empty($url[1]) ? $url[1] : 'index';
        $params = array_slice($url, 2);

        $controllerFile = 'app/controllers/' . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerName();

            if (method_exists($controller, $methodName)) {
                call_user_func_array([$controller, $methodName], $params);
            } else {
                echo "❌ Method '$methodName' không tồn tại trong $controllerName.";
            }
        } else {
            echo "❌ Controller '$controllerName' không tồn tại.";
        }
    }
}
?>