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

    public function add()
    {
        $id = $_GET['id'] ?? null; // Vérifier si l'ID de l'article est défini
        if ($id !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment = new Comment(
                $_POST['content'],
                intval($id) // Convertir en entier
            );
            $comment->setAuthor($_SESSION['username']);
            $this->commentRepository->save($comment);
            Application::$app->response->redirect('/articles');
        }

        return $this->twig->display('Comments/add.html.twig');
    }

    public function supprimer()
    {
        $id = $_GET['id'] ?? null; // Vérifier si l'ID du commentaire est défini
        if ($id !== null) {
            $this->commentRepository->delete($id);
        }
        Application::$app->response->redirect('/articles');
    }
}
