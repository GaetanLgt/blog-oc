<?php
namespace App\Controller;

use App\Core\Request;
use App\Models\Article;
use App\Core\Controller;
use App\Core\Application;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;

class ArticlesController extends Controller
{
    private $articleRepository;

    public function index()
    {
        $this->articleRepository = new ArticleRepository();
        $articles = $this->articleRepository->findAll();
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $article = new Article(
                0,
                $_POST['title'],
                $_POST['chapo'],
                $_POST['content'],
                null,
                $_POST['author'],
                date('now')
            );
            $this->articleRepository = new ArticleRepository();
            $this->articleRepository->create($article);
            Application::$app->response->redirect('/articles');
        }

        return $this->twig->display('Articles/add.html.twig');
    }

    public function supprimer()
    {
        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $this->articleRepository->delete($id);
        Application::$app->response->redirect('/articles');
    }

    public function modifier()
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
            $this->articleRepository->update($article->getId());
            Application::$app->response->redirect('/articles');
        }
        return $this->twig->display('Articles/modifier.html.twig', ['article' => $article]);
    }

    public function publish()
    {
        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $article = $this->articleRepository->findById($id);
        $article->setPublishedAt(date('now'));
        $this->articleRepository->setIsPublished(true);
        $this->articleRepository->update($article->getId());
        Application::$app->response->redirect('/articles');
    }

    public function unpublish()
    {
        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $article = $this->articleRepository->findById($id);
        $this->articleRepository->setIsPublished(false);
        $this->articleRepository->update($article->getId());
        Application::$app->response->redirect('/articles');
    }

    public function setIsPublished(bool $isPublished)
    {
        
        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $article = $this->articleRepository->findById($id);
        $this->articleRepository->setIsPublished(false);
    }
}
