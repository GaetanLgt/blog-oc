<?php

namespace App\Repository;

use App\Core\Database;
use App\Models\Comment;

class CommentRepository
{
    public function save(Comment $comment)
{
    $db = Database::getInstance();
    $sql = "INSERT INTO comments (content, author, created_at, updated_at, article_id) VALUES (:content, :author, :created_at, :updated_at, :article_id)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':content', $comment->getContent());
    $stmt->bindValue(':author', $comment->getAuthor());
    $stmt->bindValue(':created_at', $comment->getCreatedAt());
    $stmt->bindValue(':updated_at', $comment->getUpdatedAt());
    $stmt->bindValue(':article_id', $comment->getArticleId());
    $stmt->execute();
}

    public function findAll($id): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM comments WHERE article_id = :article_id');
        $stmt->execute(['article_id' => $id]);
        $stmt->execute();
        $commentsData = $stmt->fetchAll();
        $comments = [];
        foreach ($commentsData as $data) {
            $comments[] = $this->hydrate($data);
        }
        var_dump($comments);
        die();
        return $comments;
    }

    public function findById(int $id): ?Comment
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM comments WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        if (!$data) {
            return null;
        }
        return $this->hydrate($data);
    }

    public function hydrate(array $data): Comment
    {
        return new Comment(
            $data['content'],
            (int) $data['article_id']
        );
    }

    public function update()
    {
        $db = Database::getInstance();
        $sql = "UPDATE comments SET content = :content, author = :author, updated_at = :updated_at WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':content', $_POST['content']);
        $stmt->bindValue(':author', $_POST['author']);
        $stmt->bindValue(':updated_at', date('Y-m-d H:i:s'));
        $stmt->bindValue(':id', $_POST['id']);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function delete(int $id)
    {
        $db = Database::getInstance();
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}