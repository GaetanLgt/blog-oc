<?php

namespace App\Repository;

use PDO;
use Exception;
use App\Core\Database;
use App\Models\Article;
use App\Models\Comment;

class ArticleRepository
{

    public function findAll(): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM articles');
        $stmt->execute();
        $articlesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $articles = [];
        foreach ($articlesData as $data) {
            $articles[] = $this->hydrate($data);
        }
        return $articles;
    }

    public function findById(int $id): ?Article
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM articles WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        return $this->hydrate($data);
    }

    private function hydrate(array $data): Article
    {
        return new Article(
            $data['id'],
            $data['title'],
            $data['chapo'],
            $data['content'],
            $data['image'] ?? null,
            $data['author'],
            $data['published_at']
        );
    }

    public function create()
    {

        $date = date('Y-m-d H:i:s');
        $db = Database::getInstance();
        $stmt = $db->prepare('INSERT INTO articles (title, chapo, content, image, author, published_at) VALUES (:title, :chapo, :content, :image, :author, :published_at)');
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $images = $this->setImage();
        }
        $stmt->execute([
            'title' => $_POST['title'],
            'chapo' => $_POST['chapo'],
            'content' => $_POST['content'],
            'image' => $images ?? '',
            'author' => $_POST['author'],
            'published_at' => $date
        ]);
    }

    public function delete(int $id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('DELETE FROM articles WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function update(int $id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE articles SET title = :title, chapo = :chapo, content = :content, image = :image, author = :author WHERE id = :id');
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $images = $this->setImage();
        }
        $stmt->execute([
            'id' => $id,
            'title' => $_POST['title'],
            'chapo' => $_POST['chapo'],
            'content' => $_POST['content'],
            'image' => $images,
            'author' => $_POST['author']
        ]);
    }

    public function setImage()
    {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $images = $_FILES['image']['name'];
        
        $images = $_FILES['image']['name'];
        $tmpName = $_FILES['image']['tmp_name'];
        move_uploaded_file($tmpName, 'Assets/images/' . $images);
        return $images;
    } else {
        return null;
    }

    }

    public function getArticlesComments($articleId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM comments WHERE article_id = :article_id');
        $stmt->execute(['article_id' => $articleId]);
        $commentsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $comments = [];
        foreach ($commentsData as $data) {
            $comments[] = $this->hydrateComment($data);
        }
        return $comments;
    }
    
    private function hydrateComment(array $data): Comment
    {
        return new Comment(
            $data['id'],
            $data['content'],
            $data['author'],
            $data['created_at'],
            $data['updated_at'],
            $data['article_id']
        );
    }

    public function getComments()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM comments');
        $stmt->execute();
        $commentsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $comments = [];
        foreach ($commentsData as $data) {
            $comments[] = $this->hydrateComment($data);
        }
        return $comments;
    }

    public function deleteComment(int $id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('DELETE FROM comments WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }


    public function updateComment(int $id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE comments SET content = :content, author = :author, updated_at = :updated_at WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'content' => $_POST['content'],
            'author' => $_POST['author'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function createComment(int $articleId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('INSERT INTO comments (content, author, created_at, updated_at, article_id) VALUES (:content, :author, :created_at, :updated_at, :article_id)');
        $stmt->execute([
            'content' => $_POST['content'],
            'author' => $_POST['author'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'article_id' => $articleId
        ]);

    }

    public function setIsPublished()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE articles SET is_published = :is_published WHERE id = :id');
        $stmt->execute([
            'id' => $_GET['id'],
            'is_published' => 1
        ]);
    }

    public function getPublishedArticles()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM articles WHERE is_published = :is_published');
        $stmt->execute(['is_published' => 1]);
        $articlesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $articles = [];
        foreach ($articlesData as $data) {
            $articles[] = $this->hydrate($data);
        }
        return $articles;
    }
}
