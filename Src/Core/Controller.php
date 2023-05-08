<?php

namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller
{
    private $loader;
    protected $twig;

    public function __construct()
    {
        // on paramètre le dossier contenant les vues
        $this->loader = new FilesystemLoader(dirname(dirname(dirname(__FILE__))).'/views');
        // on instancie l'environnement twig
        $this->twig = new Environment($this->loader, [
            'cache' => false,
        ]);
    }

    public function renderView($view, $parameters = [])
    {
        $template = $this->twig->load($view);
        echo $template->render($parameters);
    }
}
