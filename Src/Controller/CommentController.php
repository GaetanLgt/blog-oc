<?php

namespace App\Controller;

use App\Core\Request;
use App\Models\Comment;
use App\Core\Controller;
use App\Core\Application;
use App\Repository\CommentRepository;

class CommentController extends Controller
{
    private $commentRepository;

    public function __construct()
    {
        $this->commentRepository = new CommentRepository();
    }

    public function add(): void
    {
        $id = $_GET['id'] ?? null; // Vérifier si l'ID de l'article est défini
        if ($id !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment = new Comment(
                0,
                $_POST['content'],
                $_POST['author'],
                date('Y-m-d H:i:s'), // Utilisez 'Y-m-d H:i:s' pour obtenir le format correct de la date
                $id
            );
            $this->commentRepository->save($comment);
            Application::$app->response->redirect('/articles');
        }

        return $this->twig->display('Comments/add.html.twig');
    }

    public function supprimer(): void
    {
        $id = $_GET['id'] ?? null; // Vérifier si l'ID du commentaire est défini
        if ($id !== null) {
            $this->commentRepository->delete($id);
        }
        Application::$app->response->redirect('/articles');
    }
}
