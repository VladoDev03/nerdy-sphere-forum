<?php
function fetchImages($conn, $postId) {
    $imageSql = "
        SELECT
            id,
            image_url
        FROM post_image
        WHERE post_id = ?;
    ";

    $imageStmt = $conn->prepare($imageSql);
    $imageStmt->bind_param("i", $postId);
    $imageStmt->execute();
    $imageStmt->bind_result($imageId, $imageUrl);

    $images = [];

    while ($imageStmt->fetch()) {
        $images[] = ['id' => $imageId, 'url' => $imageUrl];
    }

    $imageStmt->close();

    return $images;
}

function fetchComments($conn, $postId) {
    $commentSql = "
        SELECT
            c.id,
            c.content,
            c.created_at,
            u.id AS 'user id',
            u.username
        FROM comment AS c
        INNER JOIN user AS u ON u.id = c.user_id
        WHERE post_id = ?
        AND parent_comment_id IS NULL;
    ";

    $commentStmt = $conn->prepare($commentSql);
    $commentStmt->bind_param("i", $postId);
    $commentStmt->execute();
    $commentStmt->bind_result($commentId, $commentContent, $commentCreatedAt, $commentUserId, $commentUsername);

    $comments = [];

    while ($commentStmt->fetch()) {
        $replies = [];

        $comments[] = [
            'id' => $commentId,
            'content' => $commentContent,
            'created_at' => $commentCreatedAt,
            'replies' => $replies,
            'userId' => $commentUserId,
            'username' => $commentUsername
        ];
    }

    $commentStmt->close();

    return $comments;
}
function fetchReplies($conn, $post) {
    foreach ($post['comments'] as $commentIndex => $comment) {
        $commentId = $comment['id'];

        $replySql = "
            SELECT
                c.id AS 'reply id',
                c.content,
                c.created_at,
                u.id AS 'user id',
                u.username
            FROM comment AS c
            INNER JOIN user AS u ON u.id = c.user_id
            WHERE c.parent_comment_id = ?;
        ";

        $replyStmt = $conn->prepare($replySql);
        $replyStmt->bind_param("i", $commentId);
        $replyStmt->execute();
        $replyStmt->bind_result($replyId, $replyContent, $replyCreatedAt, $replyUserId, $replyUsername);

        $replies = [];

        while ($replyStmt->fetch()) {
            $replies[] = [
                'id' => $replyId,
                'content' => $replyContent,
                'created_at' => $replyCreatedAt,
                'userId' => $replyUserId,
                'username' => $replyUsername
            ];
        }

        $replyStmt->close();

        $post['comments'][$commentIndex]['replies'] = $replies;
    }

    return $post;
}

function fetchVotesCount($conn, $postId, $currentUserId) {
    $userVoteStmt = $conn->prepare("SELECT reaction FROM user_reaction WHERE user_id = ? AND post_id = ?");
    $userVoteStmt->bind_param("ii", $currentUserId, $postId);
    $userVoteStmt->execute();
    $userVoteStmt->bind_result($userVote);
    $userVoteStmt->fetch();
    $userVoteStmt->close();

    $countStmt = $conn->prepare("
        SELECT 
            SUM(CASE WHEN reaction = 'Like' THEN 1 ELSE 0 END) AS likeCount,
            SUM(CASE WHEN reaction = 'Dislike' THEN 1 ELSE 0 END) AS dislikeCount
        FROM user_reaction 
        WHERE post_id = ?
    ");

    $countStmt->bind_param("i", $postId);

    if ($countStmt->execute()) {
        $countStmt->bind_result($likeCount, $dislikeCount);
        $countStmt->fetch();
        $countStmt->close();

        $totalVotes = $likeCount + $dislikeCount;
        $voteDifference = $likeCount - $dislikeCount;

        return [
            'userVote' => $userVote,
            'likeCount' => $likeCount,
            'dislikeCount' => $dislikeCount,
            'totalVotes' => $totalVotes,
            'voteDifference' => $voteDifference
        ];
    } else {
        return null;
    }
}
