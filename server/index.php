<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

require_once "configuration/database.php";
require_once "utils/db_post_data.php";

$postSql = "
    SELECT
        p.id,
        p.title,
        p.content,
        p.category,
        p.created_at,
        u.id AS user_id,
        u.username,
        count(c.id) AS comments_count
    FROM post AS p
    LEFT JOIN user AS u ON u.id = p.user_id
    LEFT JOIN comment AS c ON c.post_id = p.id
    GROUP BY p.id;
";

$postStmt = $conn->prepare($postSql);
$postStmt->execute();
$postStmt->bind_result($postId, $title, $content, $category, $createdAt, $userId, $username, $commentsCount);

echo $commentsCount;

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
        'comments_count' => $commentsCount
    ];
}

$postStmt->close();

$posts = [];

foreach ($postData as $post) {
    $postId = $post['id'];

    $images = fetchImages($conn, $postId);
    $comments = fetchComments($conn, $postId);

    $posts[] = array_merge($post, [
        'images' => $images,
        'comments' => $comments
    ]);
}

foreach ($posts as $postIndex => $post) {
    $posts[$postIndex] = fetchReplies($post, $conn);
}

//echo json_encode($posts, JSON_PRETTY_PRINT);
?>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    $content = $data['content'];
    $userId = $data['userId'];
    $postId = $data['postId'];
    $parentId = $data['parentId'] ?? null;

    if ($userId !== $_SESSION['user_id']) {
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO comment (content, post_id, user_id, parent_comment_id) VALUES (?, ?, ?, ?);");
    $stmt->bind_param("siii", $content, $postId, $userId, $parentId);

    if ($stmt->execute()) {
        exit();
    } else {
        $errors[] = "Something went wrong. Please try again later.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NerdySphere_Forum</title>
    <link rel="stylesheet" href="styles/index.css">
    <script src="js/index.js" defer></script>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

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

        <form class="comment-form" action="index.php" method="POST">
            <textarea class="comment-input" name="comment-input" id="comment-input-<?= $post['id'] ?>"
                      placeholder="Add a comment..."></textarea>
            <button id="submit-comment-<?= $post['id'] ?>" class="submit-comment">Send Comment</button>
        </form>
        <div id="response"></div>

        <details id="comment-section-<?= $post['id'] ?>" class="comment-section">
            <summary>View Comments (<?= $post['comments_count'] ?>)</summary>
            <?php foreach ($post['comments'] as $comment): ?>
                <div class="comment">
                    <p class="comment-user"><?= $comment['username'] ?>:</p>
                    <p class="comment-content"><?= $comment['content'] ?></p>
                    <p class="comment-info">Posted at: <?= $comment['created_at'] ?></p>

                    <form class="reply-form" action="index.php" method="POST">
                            <textarea class="reply-input" name="reply-input" id="reply-input-<?= $comment['id'] ?>"
                                      placeholder="Add a reply..."></textarea>
                        <input type="hidden" id="post-id-<?= $comment['id'] ?>" value="<?= $post['id'] ?>">
                        <button id="submit-reply-<?= $comment['id'] ?>" class="submit-reply">Send Reply</button>
                    </form>

                    <div id="reply-section-<?= $comment['id'] ?>">
                        <?php if (count($comment['replies']) > 0): ?>
                            <details>
                                <summary>View Replies</summary>
                                <?php foreach ($comment['replies'] as $reply): ?>
                                    <div class="comment-reply">
                                        <p class="comment-user"><?= $reply['username'] ?>:</p>
                                        <p class="comment-content"><?= $reply['content'] ?></p>
                                        <p class="comment-info">Posted at: <?= $reply['created_at'] ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </details>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endforeach; ?>
        </details>
    </div>
<?php endforeach; ?>
<script>
    const currentUsername = '<?= $_SESSION['user'] ?>';
    const currentUserId = <?= $_SESSION['user_id'] ?>;
</script>
</body>
</html>
