<?php
session_start();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

require_once "configuration/database.php";
require_once "utils/db_post_data.php";

$postId = intval($_GET['id']);

$postSql = "
    SELECT
        p.title,
        p.content,
        p.category,
        p.created_at,
        u.username,
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
$stmt->bind_result($title, $content, $category, $createdAt, $username, $commentsCount);

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
    "comments_count" => $commentsCount
];

$post['images'] = fetchImages($conn, $postId);
$post['comments'] = fetchComments($conn, $postId);
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
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<?php include 'includes/post_view.php'; ?>
</body>
</html>
