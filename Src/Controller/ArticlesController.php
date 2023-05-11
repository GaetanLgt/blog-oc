<?php
namespace App\Controller;

use App\Core\Request;
use App\Models\Article;
use App\Core\Controller;
use App\Core\Application;
use App\Core\Response;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;

class ArticlesController extends Controller
{
    private $articleRepository;

    public function index(): void
    {
        $this->articleRepository = new ArticleRepository();
        $articles = $this->articleRepository->getPublishedArticles();
        return $this->twig->display('Articles/index.html.twig', ['articles' => $articles]);
    }

    public function show(): void
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
    

    public function add(): void
    {
        $this->articleRepository = new ArticleRepository();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $article = new Article(
                0,
                $_POST['title'],
                $_POST['chapo'],
                $_POST['content'],
                $_POST['image'] ?? '',
                $_POST['author'],
                intval($_POST['is_published']),
                date('Y-m-d H:i:s') // Utilisez 'Y-m-d H:i:s' pour obtenir le format correct de la date
            );
            $this->articleRepository->create();
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $this->articleRepository->setImage();
            }
            if (isset($_POST['is_published']) && $_POST['is_published'] == true ) {
                $id = $this->articleRepository->getLastInsertedId();
                $this->articleRepository->setIsPublished($id);
            }
            Application::$app->response->redirect('/articles');
        }

        return $this->twig->display('Articles/add.html.twig');
    }

    public function supprimer(): void
    {
        if (!isset($_SESSION['username'])) {
            Application::$app->response->redirect('/login');
            exit;
        }
        if (!isset($_GET['id'])) {
            Application::$app->response->redirect('/articles');
            exit;
        }
        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $article = $this->articleRepository->findById($id);
        $author = $article->getAuthor();
        if ($author != $_SESSION['username'] OR $_SESSION['role'] !== 'admin') {
            Application::$app->response->redirect('/profil');
            exit;
        }
        $this->articleRepository->delete($id);
        Application::$app->response->redirect('/articles');
    }

    public function modifier(): void
    {
        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $article = $this->articleRepository->findById($id);
        $image = $this->articleRepository->setImage();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $article->setTitle($_POST['title']);
            $article->setChapo($_POST['chapo']);
            $article->setImage($image);
            $article->setContent($_POST['content']);
            $article->setAuthor($_POST['author']);
            $article->setIsPublished($_POST['is_published'] ??false);
            $this->articleRepository->update($article->getId());
            Application::$app->response->redirect('/articles');
        }
        return $this->twig->display('Articles/modifier.html.twig', ['article' => $article]);
    }

    public function publish(): void
    {
        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $_POST['is_published'] = true;
        $this->articleRepository->update($id);
        Application::$app->response->redirect('/article?id=' . $id . '');
    }

    public function unpublish(): void
    {
        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $_POST['is_published'] = false;
        $this->articleRepository->update($id);
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
