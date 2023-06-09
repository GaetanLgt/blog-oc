<?php

namespace App\Controller;

use App\Core\Response;
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
                0,
                trim($_POST['content']),
                trim($_POST['author_id']),
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

    public function publish() {
        $id = $_GET['id'] ?? null; // Vérifier si l'ID du commentaire est défini
        if ($id !== null) {
            $this->commentRepository->setIsPublished($id);
        }
        Application::$app->response->redirect('/articles');
    }
}
