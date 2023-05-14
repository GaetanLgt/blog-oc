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
        $articles = $articleRepository->findBy('author_id', Application::$session->get('user_id'));
        return $this->renderView('Profil/index.html.twig', ['articles' => $articles]);
    }

    public function checkAccess(): void
    {
        $username = Application::$session->get('username');
        if (!isset($username)) {
            Application::$app->response->redirect('/login');
        }
    }
}
