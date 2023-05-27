<?php

namespace App\Models;

use DateTime;

class Article
{
    private int $id;
    private int $author_id;
    private int $category_id;
    private string $title;
    private string $chapo;
    private string $slug;
    private string $content;
    private string $image;
    private bool $is_published;
    private datetime $created_at;
    private datetime $updated_at;

    public function __construct(int $id, int $author_id, int $category_id, string $title, string $chapo, string $slug, string $content, string $image, bool $is_published, string $updated_at)
    {
        $this->id = $id ?? 0;
        $this->author_id = $author_id;
        $this->category_id = $category_id;
        $this->title = $title;
        $this->chapo = $chapo;
        $this->slug = $slug;
        $this->content = $content;
        $this->image = $image ?? null;
        $this->is_published = $is_published;
        $this->created_at = new DateTime('now', new \DateTimeZone('Europe/Paris'));
        $this->updated_at = new DateTime("$updated_at", new \DateTimeZone('Europe/Paris'));
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
     * Get the value of category_id
     */ 
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set the value of category_id
     *
     * @return  self
     */ 
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;

        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of chapo
     */ 
    public function getChapo()
    {
        return $this->chapo;
    }

    /**
     * Set the value of chapo
     *
     * @return  self
     */ 
    public function setChapo($chapo)
    {
        $this->chapo = $chapo;

        return $this;
    }

    /**
     * Get the value of slug
     */ 
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @return  self
     */ 
    public function setSlug($slug)
    {
        $this->slug = $slug;

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
     * Get the value of image
     */ 
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */ 
    public function setImage($image)
    {
        $this->image = $image;

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
       $this->created_at = $created_at ;

       return $this;
   }

    /**
     * Get the value of updated_at
     */ 
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @return  self
     */ 
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = new DateTime('now', new \DateTimeZone('Europe/Paris'));

        return $this;
    }
}
