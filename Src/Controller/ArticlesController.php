<?php
namespace App\Controller;

use App\Core\Request;
use App\Models\Article;
use App\Core\Controller;
use App\Repository\ArticleRepository;

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
    
        $this->twig->display('Articles/show.html.twig', ['article' => $article]);
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
            header('Location: /articles');
        }

        return $this->twig->display('Articles/add.html.twig');
    }

    public function supprimer()
    {
        $id = $_GET['id'];
        $this->articleRepository = new ArticleRepository();
        $this->articleRepository->delete($id);
        header('Location: /articles');
    }
}
