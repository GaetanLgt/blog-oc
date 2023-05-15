<?php

namespace App\Repository;

use PDO;
use Exception;
use Twig\Environment;
use App\Core\Database;
use App\Core\Response;
use App\Models\Article;
use App\Models\Comment;
use App\Core\Application;
use Twig\Loader\FilesystemLoader;

class ArticleRepository
{

    public function findAll(): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM article');
        $stmt->execute();
        $articleData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $article = [];
        foreach ($articleData as $data) {
            $article[] = $this->hydrate($data);
        }
        return $article;
    }

    public function findById(int $id): ?Article
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM article WHERE id = :id');
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
            $data['author_id'],
            $data['category_id'],
            $data['title'],
            $data['chapo'],
            $data['slug'],
            $data['content'],
            $data['image'] ?? null,
            $data['is_published'] ?? false,
            $data['updated_at']
        );
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if (!in_array($extension, $allowedExtensions)) {
                    throw new Exception('Le fichier doit être une image');
                }
                if ($_FILES['image']['size'] > 100000000) {
                    throw new Exception('Le fichier ne doit pas dépasser 100Mo');
                }
                $filename = uniqid() . '.' . $extension;
                move_uploaded_file($_FILES['image']['tmp_name'], 'Assets/images/' . $filename);
                $_POST['image'] = $filename;
            } else {
                $_POST['image'] = '';
            }
            $category_id = intval($_POST['category_id']);
            $author_id = intval($_POST['author_id']);
            $categoryRepository = new CategoryRepository();
            $authorRepository = new UserRepository();
            $category = $categoryRepository->findById($category_id);
            $author = $authorRepository->findById($author_id);
            if (!$category) {
                throw new Exception('La catégorie n\'existe pas');
            }
            if (!$author) {
                throw new Exception('L\'auteur n\'existe pas');
            }

            $db = Database::getInstance();
            $sql = "INSERT INTO article (author_id, category_id, title, chapo, slug, content, image, is_published, created_at, updated_at) VALUES (:author_id, :category_id, :title, :chapo, :slug, :content, :image, :is_published, :created_at, :updated_at)";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':author_id', $author->getId());
            $stmt->bindValue(':category_id', $category->getId());
            $stmt->bindValue(':title', $_POST['title']);
            $stmt->bindValue(':chapo', $_POST['chapo']);
            $stmt->bindValue(':slug', $_POST['slug']);
            $stmt->bindValue(':content', $_POST['content']);
            $stmt->bindValue(':image', $_POST['image']);
            $stmt->bindValue(':is_published', isset($_POST['is_published']) ? 1 : 0);
            $stmt->bindValue(':created_at', date('Y-m-d H:i:s'));
            $stmt->bindValue(':updated_at', date('Y-m-d H:i:s'));
            $stmt->execute();
        }
    }

    public function update(): void
    {
        $db = Database::getInstance();
        $sql = "UPDATE article SET author_id = :author_id, category_id = :category_id, title = :title, chapo = :chapo, slug = :slug, content = :content, image = :image, is_published = :is_published, created_at = :created_at, updated_at = :updated_at WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $_POST['id']);
        $stmt->bindValue(':author_id', $_POST['author_id']);
        $stmt->bindValue(':category_id', $_POST['category_id']);
        $stmt->bindValue(':title', $_POST['title']);
        $stmt->bindValue(':chapo', $_POST['chapo']);
        $stmt->bindValue(':slug', $_POST['slug']);
        $stmt->bindValue(':content', $_POST['content']);
        $stmt->bindValue(':image', $_POST['image']);
        $stmt->bindValue(':is_published', $_POST['is_published']);
        $stmt->bindValue(':created_at', $_POST['created_at']);
        $stmt->bindValue(':updated_at', $_POST['updated_at']);
        $stmt->execute();
    }

    public function delete(int $id): void
    {
        $db = Database::getInstance();
        $sql = "DELETE FROM article WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function getPublishedArticle(): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM article WHERE is_published = 1');
        $stmt->execute();
        $articleData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $article = [];
        foreach ($articleData as $data) {
            $article[] = $this->hydrate($data);
        }
        return $article;
    }

    public function setImage(): void
    {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            if (!in_array($extension, $allowedExtensions)) {
                throw new Exception('Le fichier doit être une image');
            }
            if ($_FILES['image']['size'] > 100000000) {
                throw new Exception('Le fichier ne doit pas dépasser 100Mo');
            }
            $filename = uniqid() . '.' . $extension;
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $filename);
            $_POST['image'] = $filename;
        }
        $db = Database::getInstance();
        $sql = "UPDATE article SET image = :image WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $_POST['id']);
        $stmt->bindValue(':image', $_POST['image']);
        $stmt->execute();
    }

    public function getComments(int $id): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM comments WHERE article_id = :article_id');
        $stmt->execute(['article_id' => $id]);
        $stmt->execute();
        $commentsData = $stmt->fetchAll();
        $comments = [];
        foreach ($commentsData as $data) {
            $comments[] = $this->hydrateComment($data);
        }
        return $comments;
    }

    public function hydrateComment(array $data): Comment
    {
        return new Comment(
            $data['id'],
            $data['content'],
            $data['author_id'],
            $data['article_id']
        );
    }

    public function getAuthor(int $id): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $stmt->execute();
        $authorData = $stmt->fetch();
        return $authorData;
    }

    public function getCommentsNumber(int $id): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM comments WHERE article_id = :article_id');
        $stmt->execute(['article_id' => $id]);
        $stmt->execute();
        $commentsNumber = $stmt->fetchColumn();
        return $commentsNumber;
    }

    public function getCommentsNumberByAuthor(int $id): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM comments WHERE author_id = :author_id');
        $stmt->execute(['author_id' => $id]);
        $stmt->execute();
        $commentsNumber = $stmt->fetchColumn();
        return $commentsNumber;
    }

    public function getArticleNumberByAuthor(int $id): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM article WHERE author_id = :author_id');
        $stmt->execute(['author_id' => $id]);
        $stmt->execute();
        $articleNumber = $stmt->fetchColumn();
        return $articleNumber;
    }

    public function getArticleNumber(): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM article');
        $stmt->execute();
        $stmt->execute();
        $articleNumber = $stmt->fetchColumn();
        return $articleNumber;
    }

    public function getCommentsNumberByArticle(int $id): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM comments WHERE article_id = :article_id');
        $stmt->execute(['article_id' => $id]);
        $stmt->execute();
        $commentsNumber = $stmt->fetchColumn();
        return $commentsNumber;
    }

    public function getCommentsNumberByAuthorAndArticle(int $id, int $author_id): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM comments WHERE article_id = :article_id AND author_id = :author_id');
        $stmt->execute(['article_id' => $id, 'author_id' => $author_id]);
        $stmt->execute();
        $commentsNumber = $stmt->fetchColumn();
        return $commentsNumber;
    }

    public function getLastInsertedId(): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT MAX(id) FROM article');
        $stmt->execute();
        $lastInsertedId = $stmt->fetchColumn();
        return $lastInsertedId;
    }


    public function setIsPublished(int $id): void
    {
        $db = Database::getInstance();
        $sql = "UPDATE article SET is_published = :is_published WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':is_published', true);
        $stmt->execute();
    }

    public function findBy($key, $value): ?Article
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM article WHERE $key = :$key");
        $stmt->execute([$key => $value]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        return $this->hydrate($data);
    }

    public function getPublishedArticles()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM article WHERE is_published = 1');
        $stmt->execute();
        $articleData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $article = [];
        foreach ($articleData as $data) {
            $article[] = $this->hydrate($data);
        }
        return $article;
    }

    public function findAllBy($key, $value): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM article WHERE $key = :$key");
        $stmt->execute([$key => $value]);
        $articleData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $article = [];
        foreach ($articleData as $data) {
            $article[] = $this->hydrate($data);
        }
        return $article;
    }
}
