<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

require_once "configuration/database.php";

$userId = $_SESSION['user_id'];

$postSql = "
    SELECT
	    p.id,
        p.title,
        p.created_at,
        count(*) AS comments_count
    FROM post AS p
    INNER JOIN comment AS c ON c.post_id = p.id
    WHERE p.user_id = '$userId'
    GROUP BY p.id;
";

$stmt = $conn->prepare($postSql);
$stmt->execute();
$stmt->bind_result($postId, $title, $createdAt, $commentsCount);

$posts = [];

while ($stmt->fetch()) {
    $posts[] = [
        'id' => $postId,
        'title' => $title,
        'created_at' => $createdAt,
        '$comments_count' => $commentsCount
    ];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/my_posts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<header>
    <h1 class="page-title">My Posts</h1>
</header>
<?php foreach ($posts as $post): ?>
    <main class="container">
        <a href="post.php?id=<?= $post['id'] ?>" class="post-link">
        <div class="post">
            <div class="post-icons">
                <i class="fas fa-edit edit-icon edit"></i>
                <i class="fas fa-trash-alt delete-icon delete"></i>
            </div>
            <h2 class="post-title"><?= $post['title'] ?></h2>
            <div class="info-container">
                <p class="posted-at">Posted at: <?= $post['created_at'] ?></p>
                <p class="comments-count"><?= $post['$comments_count'] ?> comments</p>
            </div>
        </div>
        </a>
    </main>
<?php endforeach; ?>
</body>
</html>
