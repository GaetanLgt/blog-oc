<?php

namespace App\Controller;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        return $this->renderView('Main/index.html.twig');
    }
}
