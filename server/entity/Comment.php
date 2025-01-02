<?php

namespace entity;

class Comment extends BaseEntity
{
    private $content;
    private $authorId;
    private $postId;
    private $createdAt;
    private $parentCommentId;

    public function __construct($id, $content, $authorId, $postId, $createdAt, $parentCommentId)
    {
        parent::setId($id);
        $this->content = $content;
        $this->authorId = $authorId;
        $this->postId = $postId;
        $this->createdAt = $createdAt;
        $this->parentCommentId = $parentCommentId;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getAuthorId()
    {
        return $this->authorId;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getParentCommentId()
    {
        return $this->parentCommentId;
    }
}