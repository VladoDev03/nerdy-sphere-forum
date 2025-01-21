<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

require_once "configuration/database.php";
require_once "utils/db_post_data.php";

$currentUserId = $_SESSION['user_id'];

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
    WHERE u.id <> '$currentUserId'
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
    $votesData = fetchVotesCount($conn, $postId, $currentUserId);

    $posts[] = array_merge($post, [
        'images' => $images,
        'comments' => $comments,
        'votes' => $votesData
    ]);
}

foreach ($posts as $postIndex => $post) {
    $posts[$postIndex] = fetchReplies($conn, $post);
}

//echo json_encode($posts, JSON_PRETTY_PRINT);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NerdySphere_Forum</title>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/formatters.js" defer></script>
    <script src="js/delete-comment.js" defer></script>
    <script src="js/index.js" defer></script>
    <script src="js/share-post.js" defer></script>
    <script src="js/vote.js" defer></script>
    <script src="js/modal.js" defer></script>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<?php foreach ($posts as $post): ?>
    <?php include 'includes/post_view.php'; ?>
<?php endforeach; ?>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/modal.php'; ?>
</body>
</html>
