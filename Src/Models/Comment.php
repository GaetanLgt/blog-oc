<?php

namespace App\Models;

class Comment
{
    public int $id;
    public string $content;
    public string $author;
    public string $created_at;
    public string $updated_at;
    public int $article_id;

    public function __construct(int $id, string $content, string $author, string $updated_at, int $article_id)
    {
        $this->id = $id ?? 0;
        $this->content = $content;
        $this->author = $author;
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = $updated_at;
        $this->article_id = $article_id;
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of content
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of author
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @return  self
     */
    public function setAuthor($author): self
    {
        $author = $_SESSION['username'];
        $this->author = $author;
        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */
    public function setCreatedAt($created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of updated_at
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @return  self
     */
    public function setUpdatedAt($updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get the value of article_id
     */
    public function getArticleId(): int
    {
        return $this->article_id;
    }

    /**
     * Set the value of article_id
     *
     * @return  self
     */
    public function setArticleId($article_id): self
    {
        $this->article_id = $article_id;

        return $this;
    }
}
