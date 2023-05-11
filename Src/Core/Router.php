<?php
namespace App\Core;

use App\Core\Request;
use App\Core\Response;
use App\Core\Controller;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback): void
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback): void
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            $this->response->setStatusCode(404);
            $controller = new Controller();
            return $controller->renderView('_404/index.html.twig');
        }

        if (is_string($callback)) {
            $controller = new Controller();
            return $controller->renderView($callback);
        }

        if (is_array($callback)) {
            $controller = new $callback[0]();
            $method = $callback[1];
            return $controller->$method();
        }

        return call_user_func($callback);
    }
}
