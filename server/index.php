<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

require_once "database.php";

$postSql = "
    SELECT
        p.id,
        p.title,
        p.content,
        p.category,
        p.created_at,
        u.id AS user_id,
        u.username
    FROM post AS p
    INNER JOIN user AS u ON u.id = p.user_id;
";

$postStmt = $conn->prepare($postSql);
$postStmt->execute();
$postStmt->bind_result($postId, $title, $content, $category, $createdAt, $userId, $username);

$postData = [];

while ($postStmt->fetch()) {
    $postData[] = [
        'id' => $postId,
        'title' => $title,
        'content' => $content,
        'category' => $category,
        'created_at' => $createdAt,
        'user_id' => $userId,
        'username' => $username,
    ];
}

$postStmt->close();

$posts = [];

foreach ($postData as $post) {
    $postId = $post['id'];

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

    $posts[] = array_merge($post, [
        'images' => $images,
        'comments' => $comments
    ]);
}

foreach ($posts as $postIndex => $post) {
    foreach ($post['comments'] as $commentIndex => $comment) {
        $commentId = $comment['id'];
        $commentContent = $comment['content'];
        $commentCreatedAt = $comment['created_at'];

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

    $posts[$postIndex] = $post;
}

$conn->close();

//echo json_encode($posts, JSON_PRETTY_PRINT);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NerdySphere_Forum</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>

<div class="navbar">
    <div class="left-navigation">
        <a class="nav-url" href="logout.php">Logout</a>
    </div>
    <h1 class="nav-title">NerdySphere_Forum</h1>
    <div class="right-navigation">
        <a class="nav-url" href="profile.php">Profile</a>
        <a class="nav-url" href="new_post.php">Create Post</a>
    </div>
</div>

<?php foreach ($posts as $post): ?>
    <div class="post">
        <div class="post-body">
            <p class="post-info">Posted by: <?= $post['username'] ?> | Category: <?= $post['category'] ?> | Posted
                at: <?= $post['created_at'] ?></p>
            <h2 class="title"><?= $post['title'] ?></h2>
            <p class="content"><?= $post['content'] ?></p>

            <?php if (isset($post['images'])): ?>
                <div class="post-images">
                    <?php foreach ($post['images'] as $image): ?>
                        <img class="image" src="<?= $image['url'] ?>" alt="Post Image">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <form class="comment-form" action="#">
            <textarea class="comment-input" placeholder="Add a comment..."></textarea>
            <button class="submit-comment">Send Comment</button>
        </form>

        <?php if (count($post['comments']) > 0): ?>
            <div class="comment-section">
                <?php foreach ($post['comments'] as $comment): ?>
                    <div class="comment">
                        <p class="comment-user"><?= $comment['username'] ?>:</p>
                        <p class="comment-content"><?= $comment['content'] ?></p>
                        <p class="comment-info">Posted at: <?= $comment['created_at'] ?></p>
                        <textarea class="reply-input" placeholder="Add a reply..."></textarea>
                        <button class="submit-reply">Send Reply</button>

                        <div class="comment-replies">
                            <?php foreach ($comment['replies'] as $reply): ?>
                                <div class="comment-reply">
                                    <p class="comment-user"><?= $reply['username'] ?>:</p>
                                    <p class="comment-content"><?= $reply['content'] ?></p>
                                    <p class="comment-info">Posted at: <?= $reply['created_at'] ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

</body>
</html>
