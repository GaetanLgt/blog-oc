<?php

namespace App\Repository;

use App\Core\Application;
use App\Core\Database;
use App\Models\Comment;

class CommentRepository
{
    public function findAll(int $article_id): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM comment WHERE article_id = :article_id');
        $stmt->execute(['article_id' => $article_id]);
        $commentData = $stmt->fetchAll();
        $comment = [];
        foreach ($commentData as $data) {
            $comment[] = $this->hydrate($data);
        }
        return $comment;
    }

    public function create(): void
    {
        $db = Database::getInstance();
        $sql = "INSERT INTO comment (content, author_id, article_id, is_published, created_at) VALUES (:content, :author_id, :article_id, :is_published, :created_at)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':content', $_POST['content']);
        $stmt->bindValue(':author_id', $_POST['author_id']);
        $stmt->bindValue(':article_id', $_POST['article_id']);
        $stmt->bindValue(':is_published', false );
        $stmt->bindValue(':created_at', $_POST['created_at']);
        $stmt->execute();
    }

    private function hydrate(array $data): Comment
    {
        return new Comment(
            $data['id'],
            $data['content'],
            $data['author_id'],
            $data['article_id'],
            $data['is_published']
        );
    }

    public function delete(int $id): void
    {
        $db = Database::getInstance();
        $sql = "DELETE FROM comment WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function save(Comment $comment): void
    {
        $db = Database::getInstance();
        $sql = "INSERT INTO comment (content, author_id, article_id, is_published, created_at,username) VALUES (:content, :author_id, :article_id, :is_published, :created_at,:username)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':content', $comment->content);
        $stmt->bindValue(':author_id', $comment->author_id);
        $stmt->bindValue(':article_id', $comment->article_id);
        $stmt->bindValue(':is_published', ($comment->is_published) ? 0 : 1);
        $stmt->bindValue(':created_at', date('Y-m-d H:i:s'));
        $stmt->bindValue(':username', Application::$session->get('username'));
        $stmt->execute();
    }

    public function update(Comment $comment): void
    {
        $db = Database::getInstance();
        $sql = "UPDATE comment SET content = :content, author_id = :author_id, article_id = :article_id, is_published = :is_published, created_at = :created_at WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':content', $comment->content);
        $stmt->bindValue(':author_id', $comment->author_id);
        $stmt->bindValue(':article_id', $comment->article_id);
        $stmt->bindValue(':is_published', $comment->is_published);
        $stmt->bindValue(':created_at', $comment->created_at);
        $stmt->bindValue(':id', $comment->id);
        $stmt->execute();
    }

    public function find(int $id): ?Comment
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM comment WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        if (!$data) {
            return null;
        }
        return $this->hydrate($data);
    }

    public function findAllByArticle($article_id){
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM comment WHERE article_id = :article_id');
        $stmt->execute(['article_id' => $article_id]);
        $commentData = $stmt->fetchAll();
        $comment = [];
        foreach ($commentData as $data) {
            $comment[] = $this->hydrate($data);
        }
        return $comment;
    }

    public function deleteAll(int $article_id): void
    {
        $db = Database::getInstance();
        $sql = "DELETE FROM comment WHERE article_id = :article_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':article_id', $article_id);
        $stmt->execute();
    }

    public function setIsPublished(int $id): void
    {
        $db = Database::getInstance();
        $sql = "UPDATE comment SET is_published = 1 WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function unpublish(int $id): void
    {
        $db = Database::getInstance();
        $sql = "UPDATE comment SET is_published = 0 WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function count(): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM comment');
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count;
    }

    public function countPublished(): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM comment WHERE is_published = 1');
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count;
    }

    public function countUnpublished(): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM comment WHERE is_published = 0');
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count;
    }

    public function countByArticle(int $article_id): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM comment WHERE article_id = :article_id');
        $stmt->execute(['article_id' => $article_id]);
        $count = $stmt->fetchColumn();
        return $count;
    }

    public function countPublishedByArticle(int $article_id): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM comment WHERE article_id = :article_id AND is_published = 1');
        $stmt->execute(['article_id' => $article_id]);
        $count = $stmt->fetchColumn();
        return $count;
    }

    public function findAllPublishedByArticle($article_id){
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM comment WHERE article_id = :article_id AND is_published = 1');
        $stmt->execute(['article_id' => $article_id]);
        $commentData = $stmt->fetchAll();
        $comment = [];
        foreach ($commentData as $data) {
            $comment[] = $this->hydrate($data);
        }
        return $comment;
    }
}
