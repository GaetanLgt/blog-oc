<?php

namespace App\Models;

use DateTime;
use App\Repository\UserRepository;

class Comment
{
    public int $id;
    public string $content;
    public int $author_id;
    public string $username;
    public int $article_id;
    public datetime $created_at;
    public bool $is_published;

    public function __construct(int $id, string $content, int $author_id, int $article_id,bool $is_published = false)
    {
        $this->id = $id;
        $this->content = $content;
        $this->author_id = $author_id;
        $author = new UserRepository();
        $this->username = $author->getUsername($author_id);
        $this->article_id = $article_id;
        $this->created_at = new DateTime('now', new \DateTimeZone('Europe/Paris'));
        $this->is_published = $is_published;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of content
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of author_id
     */ 
    public function getAuthorId()
    {
        return $this->author_id;
    }

    /**
     * Set the value of author_id
     *
     * @return  self
     */ 
    public function setAuthorId($author_id)
    {
        $this->author_id = $author_id;

        return $this;
    }

    /**
     * Get the value of article_id
     */ 
    public function getArticleId()
    {
        return $this->article_id;
    }

    /**
     * Set the value of article_id
     *
     * @return  self
     */ 
    public function setArticleId($article_id)
    {
        $this->article_id = $article_id;

        return $this;
    }

    /**
     * Get the value of created_at
     */ 
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */ 
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of is_published
     */ 
    public function getIsPublished()
    {
        return $this->is_published;
    }

    /**
     * Set the value of is_published
     *
     * @return  self
     */ 
    public function setIsPublished($is_published)
    {
        $this->is_published = $is_published;

        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }
}
