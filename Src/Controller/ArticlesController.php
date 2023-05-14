<?php

namespace App\Controller;

use App\Core\Request;
use App\Models\Article;
use App\Core\Controller;
use App\Core\Application;
use App\Core\Response;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;

class ArticlesController extends Controller
{
    private ArticleRepository $articleRepository;

    public function index()
    {
        $this->articleRepository = new ArticleRepository();
        $articles = $this->articleRepository->getPublishedArticles();
        return $this->twig->display('Articles/index.html.twig', ['articles' => $articles]);
    }

    public function show()
    {
        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $article = $this->articleRepository->findById($id);

        if (!$article) {
            // Si l'article n'existe pas, afficher une erreur 404
            $controller = new Controller();
            return $controller->renderView('_404/index.html.twig');
        }
        $commentRepository = new CommentRepository();
        $comments = $commentRepository->findAll($id);


        return $this->twig->display('Articles/show.html.twig', ['article' => $article, 'comments' => $comments]);
    }


    public function add()
    {
        if (!isset($_SESSION['username'])) {
            Application::$app->response->redirect('/login');
        }
        $categories = [];
        $categoriesRepository = new CategoryRepository();
        $categories = $categoriesRepository->findAll();
        $this->articleRepository = new ArticleRepository();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->articleRepository->create();
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $this->articleRepository->setImage();
            }
            if (isset($_POST['is_published']) && $_POST['is_published'] === true) {
                $id = $this->articleRepository->getLastInsertedId();
                $this->articleRepository->setIsPublished($id);
            }
            Application::$app->response->redirect('/articles');
        }

        return $this->twig->display('Articles/add.html.twig', ['categories' => $categories]);
    }

    public function supprimer(): void
    {
        if (!isset($_SESSION['username'])) {
            Application::$app->response->redirect('/login');
        }
        if (!isset($_GET['id'])) {
            Application::$app->response->redirect('/articles');
        }
        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $article = $this->articleRepository->findById($id);
        $author = $article->getAuthor_id();
        if ($author != $_SESSION['username'] || $_SESSION['role'] !== 'admin') {
            Application::$app->response->redirect('/profil');
        }
        $this->articleRepository->delete($id);
        Application::$app->response->redirect('/articles');
    }

    public function modifier()
    {
        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $article = $this->articleRepository->findById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $article->setTitle(trim($_POST['title']));
            $article->setChapo(trim($_POST['chapo']));
            $article->setImage(trim($_POST['image']));
            $article->setContent(trim($_POST['content']));
            $article->setAuthor_id(trim($_POST['author']));
            $article->setIsPublished(trim($_POST['is_published']) ?? false);
            $this->articleRepository->update();
            Application::$app->response->redirect('/articles');
        }
        return $this->twig->display('Articles/modifier.html.twig', ['article' => $article]);
    }

    public function publish(): void
    {
        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $_POST['is_published'] = true;
        $this->articleRepository->update();
        Application::$app->response->redirect('/article?id=' . $id . '');
    }

    public function unpublish(): void
    {
        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $_POST['is_published'] = false;
        $this->articleRepository->update();
        Application::$app->response->redirect('/articles');
    }

    public function setIsPublished(bool $isPublished): void
    {

        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $article = $this->articleRepository->findById($id);
        $article->setIsPublished($isPublished);
    }
}
