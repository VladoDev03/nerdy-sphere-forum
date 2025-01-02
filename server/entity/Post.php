<?php

namespace entity;

use Category;

class Post extends BaseEntity
{
    private $title;
    private $content;
    private $createdAt;
    private Category $category;
    private $authorId;

    public function __construct($id, $title, $content, $createdAt, Category $category, $authorId)
    {
        parent::setId($id);
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->category = $category;
        $this->authorId = $authorId;
    }

    public function getAuthorId()
    {
        return $this->authorId;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getTitle()
    {
        return $this->title;
    }
}