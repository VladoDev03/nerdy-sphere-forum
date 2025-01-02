<?php

namespace entity;

class postImage extends BaseEntity
{
    private $postId;
    private $imageUrl;
    private $publicId;

    public function __construct($id, $postId, $publicId, $imageUrl)
    {
        parent::setId($id);
        $this->postId = $postId;
        $this->publicId = $publicId;
        $this->imageUrl = $imageUrl;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    public function getPublicId()
    {
        return $this->publicId;
    }
}