<?php

namespace App\Core;
use App\Core\Middlewares\Auth;
use App\Core\Session;

class Router
{
    private static ?Router $instance = null;

    public static function getInstance(): Router
    {
        if (self::$instance === null) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    public static function resolve($uris)
    {
        Session::getInstance();

        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $parsedUri = parse_url($requestUri);
        $currentUri = rtrim($parsedUri['path'] ?? '/', '/') ?: '/';

        if (isset($uris[$currentUri])) {
            $route = $uris[$currentUri];
            $controllerClass = $route['controller'];
            $method = $route['method'];
            $middlewares = $route['middlewares'] ?? [];

            self::runMiddlewares($middlewares);

            if (!class_exists($controllerClass)) {
                $classFile = str_replace(['Src\\Controller\\', '\\'], ['../src/controller/', '/'], $controllerClass) . '.php';
                if (file_exists($classFile)) {
                    require_once $classFile;
                }
            }

            $controller = new $controllerClass();
            $controller->$method();
        } else {
            http_response_code(404);
            echo "<h1>404 - Page Not Found</h1>";
        }
    }

    private static function runMiddlewares(array $middlewares): void
    {
        foreach ($middlewares as $middleware) {
            switch ($middleware) {
                case 'auth':
                    // Utilise la classe Session pour vérifier l'utilisateur
                    if (!Session::get('user')) {
                        header('Cache-Control: no-cache, no-store, must-revalidate');
                        header('Location: /');
                        exit();
                    }
                    break;

                default:
                    break;
            }
        }
    }
}
