<?php

namespace App\Models;

class Article
{
    private int $id;
    private string $title;
    private string $chapo;
    private string $content;
    private ?string $image;
    private string $author;
    private bool $is_published;
    private string $published_at;

    public function __construct(int $id, string $title, string $chapo, string $content, ?string $image, string $author,bool $is_published, string $published_at)
    {
        $this->id = $id ?? 0;
        $this->title = $title;
        $this->chapo = $chapo;
        $this->content = $content;
        $this->image = $image;
        $this->author = $author;
        $this->is_published = $is_published ?? false;
        $this->published_at = date('Y-m-d H:i:s');

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
     * Get the value of title
     */ 
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of chapo
     */ 
    public function getChapo(): string
    {
        return $this->chapo;
    }

    /**
     * Set the value of chapo
     *
     * @return  self
     */ 
    public function setChapo($chapo): self
    {
        $this->chapo = $chapo;

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
     * Get the value of image
     */ 
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */ 
    public function setImage($image): self
    {
        $this->image = $image;

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
        $this->author = $author;

        return $this;
    }

    /**
     * Get the value of is_published
     */ 
    public function getIsPublished(): bool
    {
        return $this->is_published;
    }

    /**
     * Set the value of is_published
     *
     * @return  self
     */ 
    public function setPublishedAt($is_published): self
    {
        $this->is_published = $is_published;
        return $this;
    }

    /**
     * Get the value of published_at
     */ 
    public function getPublishedAt(): string
    {
        return $this->published_at;
    }

    /**
     * Set the value of is_published
     *
     * @return  self
     */ 
    public function setIspublished($is_published): self
    {
        $this->is_published = $is_published;

        return $this;
    }
}
