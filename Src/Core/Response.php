<?php

namespace App\Core;

class Response
{
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    public function getStatusCode(): int
    {
        return http_response_code();
    }

    public function redirect($route): void
    {
        header('Location: ' . $route);
    }

}