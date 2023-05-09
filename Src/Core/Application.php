<?php

namespace App\Core;


use App\Models\User;
use App\Core\Session;

class Application
{
    public static string $ROOT_DIR;
    public Request $request;
    public Response $response;
    public Router $router;
    public static Application $app;
    public ?User $user;
    public static Session $session;

    public function __construct()
    {
        $session = new Session();
        $session->init();
        self::$ROOT_DIR = APP_ROOT;
        self::$app = $this;
        $this->response = new Response();
        $this->request = new Request();
        $this->router = new Router($this->request, $this->response);
        self::$session = $session;
    }

    public function run()
    {
        $this->router->resolve();
    }
}
