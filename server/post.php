<?php
session_start();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

require_once "database.php";

$postId = intval($_GET['id']);

$postSql = "
    SELECT
        p.title,
        p.content,
        p.created_at
    FROM post AS p
    WHERE p.id = ?;
";

$stmt = $conn->prepare($postSql);
$stmt->bind_param("i", $postId);
$stmt->execute();
$stmt->bind_result($title, $content, $createdAt);

if (!$stmt->fetch()) {
    header('Location: index.php');
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts</title>
    <link rel="stylesheet" href="my_posts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<?= $title ?>
<?= $content ?>
<?= $createdAt ?>
</body>
</html>
