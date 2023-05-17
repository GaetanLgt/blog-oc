<?php

namespace App\Controller;

use App\Core\Request;
use App\Core\Response;
use App\Models\Article;
use App\Core\Controller;
use App\Core\Application;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;

class ArticlesController extends Controller
{
    private ArticleRepository $articleRepository;
    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $active = 'blog';
        $this->articleRepository = new ArticleRepository();
        $articles = $this->articleRepository->getPublishedArticles();
        return $this->twig->display('Articles/index.html.twig', ['articles' => $articles, 'active' => $active]);
    }

    public function show()
    {
        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $article = $this->articleRepository->findById($id);
        $author = $article->getAuthorId();
        $authorRepository = new UserRepository();
        $author = $authorRepository->findById($author);
        $author = $author->getUsername();
        $categoryRepository = new CategoryRepository();
        $category = $categoryRepository->findById($article->getCategortyId());
        $category = $category->getName();
        if (!$article) {
            // Si l'article n'existe pas, afficher une erreur 404
            $controller = new Controller();
            return $controller->renderView('_404/index.html.twig');
        }
        $commentRepository = new CommentRepository();
        if( Application::$session->get('role') === 'admin') {
            $comments = $commentRepository->findAllByArticle($id);
        } else {
            // Récupérer les commentaires publiés (is_published = true
            $comments = $commentRepository->findAllPublishedByArticle($id);
        }

        return $this->twig->display('Articles/show.html.twig', ['article' => $article, 'comments' => $comments, 'author' => $author, 'category' => $category]);
    }


    public function add()
    {
        if (!isset($_SESSION['username'])) {
            Application::$app->response->redirect('/login');
        }
        if (Application::$session->get('role') !== 'admin') {
            Application::$app->response->redirect('/profil');
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
        $author = $article->getAuthorId();
        if ($author !== Application::$session->get('username') || Application::$session->get('role') !== 'admin') {
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
            $article->setAuthorId(trim($_POST['author']));
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
