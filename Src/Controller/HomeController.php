<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Response;

class HomeController extends Controller
{
    public function index(): string
    {
        return $this->twig->render('Main/index.html.twig');
    }
}
