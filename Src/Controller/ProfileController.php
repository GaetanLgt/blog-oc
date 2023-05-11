<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Application;
use App\Core\Response;
use App\Repository\ArticleRepository;

class ProfileController extends Controller
{
    public function index()
    {
        $this->checkAccess();
        $articleRepository = new ArticleRepository();
        $articles = $articleRepository->findBy('author', $_SESSION['username']);
        return $this->renderView('Profil/index.html.twig', ['articles' => $articles]);
    }

    public function checkAccess(): void
    {
        if (!isset($_SESSION['username'])) {
            Application::$app->response->redirect('/login');
        }
    }
}
