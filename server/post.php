<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

require_once "configuration/database.php";
require_once "utils/db_post_data.php";

$currentUserId = $_SESSION['user_id'];
$postId = intval($_GET['id']);

$postSql = "
    SELECT
        p.title,
        p.content,
        p.category,
        p.created_at,
        u.username,
        u.id,
        count(c.id) AS comments_count
    FROM post AS p
    LEFT JOIN user AS u ON u.id = p.user_id
    LEFT JOIN comment AS c ON c.post_id = p.id
    WHERE p.id = ?
    GROUP BY p.id;
";

$stmt = $conn->prepare($postSql);
$stmt->bind_param("i", $postId);
$stmt->execute();
$stmt->bind_result($title, $content, $category, $createdAt, $username, $userId, $commentsCount);

if (!$stmt->fetch()) {
    header('Location: index.php');
    exit();
}

$stmt->close();

$post = [
    "id" => $postId,
    "title" => $title,
    "content" => $content,
    "category" => $category,
    "created_at" => $createdAt,
    "username" => $username,
    "user_id" => $userId,
    "comments_count" => $commentsCount
];

$post['images'] = fetchImages($conn, $postId);
$post['comments'] = fetchComments($conn, $postId);
$post['votes'] = fetchVotesCount($conn, $postId, $currentUserId);
$post = fetchReplies($conn, $post);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/formatters.js" defer></script>
    <script src="js/index.js" defer></script>
    <script src="js/delete-comment.js" defer></script>
    <script src="js/vote.js" defer></script>
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<?php include 'includes/post_view.php'; ?>
<?php include 'includes/footer.php'; ?>
</body>
</html>
