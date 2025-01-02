<?php

namespace entity;

class CommentTag extends BaseEntity
{
    private $commentId;
    private $userId;

    public function __construct($id, $commentId, $userId)
    {
        parent::setId($id);
        $this->commentId = $commentId;
        $this->userId = $userId;
    }

    public function getCommentId()
    {
        return $this->commentId;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}