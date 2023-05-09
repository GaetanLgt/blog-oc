<?php

namespace App\Repository;

use PDO;
use Exception;
use App\Core\Database;
use App\Models\Article;

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
            $images = $_FILES['image']['name'];
            $tmpName = $_FILES['image']['tmp_name'];
            move_uploaded_file($tmpName, 'Assets/images/' . $images);
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
        $stmt->execute([
            'id' => $id,
            'title' => $_POST['title'],
            'chapo' => $_POST['chapo'],
            'content' => $_POST['content'],
            'image' => $_POST['image'],
            'author' => $_POST['author']
        ]);
    }
}
