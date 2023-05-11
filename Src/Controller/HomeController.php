<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Response;

class HomeController extends Controller
{
    public function index()
    {
        return $this->renderView('Main/index.html.twig');
    }
}
